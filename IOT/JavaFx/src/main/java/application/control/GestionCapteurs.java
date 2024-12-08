package application.control;


import java.io.File;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

import org.ini4j.Wini;

import application.loader.DataLoader;
import application.loader.capteursSalle.DataCapteurs;
import application.loader.capteursSalle.DataValue;
import application.view.GestionCapteursViewController;
import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.NumberAxis;
import javafx.scene.chart.XYChart;
import javafx.scene.control.Label;
import javafx.scene.control.Tooltip;
import javafx.scene.layout.BorderPane;
import javafx.scene.layout.GridPane;
import javafx.stage.Modality;
import javafx.stage.Stage;

public class GestionCapteurs {

    private Stage capStage;
    private GestionCapteursViewController capViewController;
    private ObservableList<DataCapteurs> oListCapteurs;


    Thread updateCapteurs;
    private boolean running = true;
    
        /**
         * Initialiser une fenêtre permettant de gérer les capteurs
         * et l'associe à un controlleur qui gère la logique derrière 
         * @param _parentStage la fenêtre parente 
         */
        public GestionCapteurs(Stage _parentStage) {
    
            try {
                FXMLLoader loader = new FXMLLoader(
                GestionCapteursViewController.class.getResource("SallesAffichage.fxml"));
                BorderPane root = loader.load();
    
                Scene scene = new Scene(root, root.getPrefWidth() + 50, root.getPrefHeight() + 10);
    
                this.capStage = new Stage();
                this.capStage.initModality(Modality.NONE);
                this.capStage.initOwner(_parentStage);
                this.capStage.setScene(scene);
                this.capStage.setTitle("Gestion des capteurs");
                this.capStage.setMaximized(true);
                this.capStage.setResizable(true);
    
                this.capStage.setOnCloseRequest(e -> {
                    running = false;
                    System.out.println("Arret du script Update Capteurs");
                    updateCapteurs.interrupt();
                });
    
                this.capViewController = loader.getController();
                this.capViewController.initContext(this.capStage, this);
    
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    
        /**
         * Permet de mettre à jour les capteurs avec un intervalle de 5 secondes grace à un thread
         * @param oListCapteurs la liste des capteurs 
         */
        public void updateCapteurs(ObservableList<DataCapteurs> oListCapteurs) {
            updateCapteurs = new Thread(() -> {      
                while (running) {
                    try {
                        Thread.sleep(5000);
                        
                        if(this.oListCapteurs != null){
                        ObservableList <DataCapteurs> olCapteurs = FXCollections.observableArrayList();
                        this.loadCapteurs(olCapteurs, this.capViewController.getConfigFileName());
                        Platform.runLater(() -> {

                            if (this.oListCapteurs.size() == olCapteurs.size()) {
                                for (int i = 0; i < olCapteurs.size(); i++) {
                                    if (!this.isEqual(this.oListCapteurs.get(i), olCapteurs.get(i), "CO2")) {
                                        this.capViewController.configureData(false);
                                    }
                                }    
                            } else {
                                this.capViewController.configureData(true);
                            }
                        });
                    }
                    

                } catch (InterruptedException e) {
                    e.printStackTrace();
                    Thread.currentThread().interrupt();
                }
            }
            
        });
        updateCapteurs.start();
    }

    /**
     * Permet de comparer deux listes de capteurs
     * @param capteurs 
     * @param olCapteurs 
     * @param type le type de capteurs a comparer
     * @return
     */
    private boolean isEqual(DataCapteurs capteurs, DataCapteurs olCapteurs, String type) {
        return capteurs.getValues(type).size() == olCapteurs.getValues(type).size();
    }

	
    public void doCapteursDialog(){
		this.capViewController.showDialog();
    }


    /**
     * Permet de lire une valeur dans le fichier de configuration
     * @param section Section du fichier de configuration
     * @param option Option de la section
     * @return la valeur de l'option
     */
    public String read(String section, String option){
		try{
            Wini ini = new Wini(new File(new File(getClass().getProtectionDomain().getCodeSource().getLocation().toURI()).getParent() + File.separator + "config.ini")); 
			return (ini.get(section, option));
        }catch(Exception e){
            System.err.println(e.getMessage());
        }
		return null;
	}

    /**
     * Permet de charger les capteurs depuis un fichier JSON
     * @param olCapteurs la liste des capteurs
     * @param configFileName le nom de l'option pour trouver le nom du fichier JSON
     */
	public void loadCapteurs(ObservableList<DataCapteurs> olCapteurs, String configFileName) {
        olCapteurs.clear();
        DataLoader dataLoader = new DataLoader();
        String fileName = this.read("configTopic", configFileName);
        dataLoader.LoadDatasFromJson(fileName+".json");
        List<DataCapteurs> capteurs = dataLoader.getDataLoader();
        olCapteurs.addAll(capteurs);
    }


    /**
     * Permet de créer un graphique 
     * @param yAxisName le nom de l'axe des ordonnées
     * @param title le titre du graphique
     * @return le graphique
     */
	public LineChart<Number, Number> createLineChart(String yAxisName, String title) {
        
        NumberAxis xAxis = new NumberAxis();
        NumberAxis yAxis = new NumberAxis();
		xAxis.setForceZeroInRange(false);
        xAxis.setLabel("Date");
        yAxis.setLabel(yAxisName);

		xAxis.setTickLabelFormatter(new NumberAxis.DefaultFormatter(xAxis) {
            private final SimpleDateFormat dateFormat = new SimpleDateFormat("dd-MM-yyyy");

            @Override
            public String toString(Number value) {
                return dateFormat.format(new Date(value.longValue()));
            }
        });

        LineChart<Number, Number> lineChart = new LineChart<>(xAxis, yAxis);
        lineChart.setTitle(title);
        return lineChart;
    }

    /**
     * Permet d'ajouter une série à un graphique
     * @param capteurs les capteurs
     * @param type le type de capteurs a mettre dans la série
     * @param unite l'unité de mesure des capteurs
     * @param lineChartGeneral le graphique général
     * @param gridPane le gridpane ou ajouter le graphique 
     * @param rowIndex l'index de la ligne ou ajouter le graphique
     */
	public void addSeriesToLineChart(DataCapteurs capteurs, String type, String unite, LineChart<Number, Number> lineChartGeneral, GridPane gridPane, int rowIndex) { 

		LineChart<Number, Number> lineChartSeul = this.createLineChart(unite, type+" : "+capteurs.getname());
		this.createSeries(capteurs, type, lineChartSeul, gridPane);
		this.createSeries(capteurs, type, lineChartGeneral, gridPane);
		gridPane.add(lineChartSeul, 1, rowIndex);

    }
     
    /**
     * Permet de créer une série pour un graphique
     * @param capteurs les capteurs
     * @param type le type de capteurs a mettre dans la série
     * @param lineChart le graphique
     * @param gridPane le gridpane ou ajouter le graphique
     */
    private void createSeries(DataCapteurs capteurs, String type, LineChart<Number, Number> lineChart, GridPane gridPane) {
        XYChart.Series<Number, Number> series = new XYChart.Series<>();
        series.setName(capteurs.getname());
        for (DataValue dataValue : capteurs.getValues(type)) {
            series.getData().add(new XYChart.Data<>(dateToMillis(dataValue.getDate()), dataValue.getValue()));
        }
        lineChart.getData().add(series);

		for (XYChart.Data<Number, Number> seriesData : series.getData()) {
			String date = new SimpleDateFormat("dd-MM-yyyy HH:mm:ss").format(new Date(seriesData.getXValue().longValue()));
			seriesData.getNode().setOnMouseClicked(event -> {
				Label label = new Label(type + " : " + seriesData.getYValue() +"\nDate : " + date + "\nSalle : " + capteurs.getname());
				label.setStyle( "-fx-alignment: CENTER; -fx-font-size: 16px;");
				gridPane.getChildren().removeIf(node -> GridPane.getColumnIndex(node) == 2 && GridPane.getRowIndex(node) == 0);
				gridPane.add(label, 2, 0);
			});
			seriesData.getNode().setOnMouseEntered(e -> {
                seriesData.getNode().setStyle("-fx-background-color:#B22222;");
				Tooltip.install(seriesData.getNode(), new Tooltip(type +" : " + seriesData.getYValue() +"\nDate : " + date + "\nSalle :" + capteurs.getname()));

            });
            seriesData.getNode().setOnMouseExited(e -> {
                seriesData.getNode().setStyle("");
            });
		}
    }

    /**
     * Permet de convertir une date en millisecondes
     * @param date la date a convertir
     * @return la date en millisecondes
     */
	private long dateToMillis(String date) {
        try {
            return new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").parse(date).getTime();
        } catch (Exception e) {
            throw new RuntimeException("Format de date invalide : " + date, e);
        }
    }


}

