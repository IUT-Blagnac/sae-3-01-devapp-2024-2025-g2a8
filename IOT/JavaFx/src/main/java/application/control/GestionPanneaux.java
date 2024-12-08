package application.control;

import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.XYChart;
import javafx.scene.control.TableView;
import javafx.scene.control.Tooltip;
import javafx.scene.layout.BorderPane;
import javafx.stage.Modality;
import javafx.stage.Stage;

import java.io.File;
import java.util.List;

import org.ini4j.Wini;

import application.loader.DataLoader;
import application.loader.solarPanels.DataEnergy;
import application.loader.solarPanels.DataSolarPanel;
import application.view.GestionPanneauxViewController;

public class GestionPanneaux {
    private Stage panneauStage;
    private GestionPanneauxViewController panneauxViewController;
    
    private Thread updatePanneaux;
    private boolean running = true;


    /**
     * Initialiser une fenêtre permettant de gérer les capteurs pour les panneaux solaires
     * et l'associe à un controlleur qui gère la logique derrière 
     * @param _parentStage la fenêtre parente 
     */
    public GestionPanneaux(Stage _parentStage) {

		try {
			FXMLLoader loader = new FXMLLoader(
			GestionPanneauxViewController.class.getResource("PanneauAffichage.fxml"));
			BorderPane root = loader.load();

			Scene scene = new Scene(root, root.getPrefWidth() + 50, root.getPrefHeight() + 10);

			this.panneauStage = new Stage();
			this.panneauStage.initModality(Modality.NONE);
			this.panneauStage.initOwner(_parentStage);
			this.panneauStage.setScene(scene);
			this.panneauStage.setTitle("Gestion Panneaux Solaire");
            this.panneauStage.setMaximized(true);
			this.panneauStage.setResizable(true);

			this.panneauxViewController = loader.getController();

            this.panneauStage.setOnCloseRequest(e -> {
                running = false;
                System.out.println("Arret du script Update Panneaux");
                updatePanneaux.interrupt();
            });
            
			this.panneauxViewController.initContext(this.panneauStage, this);

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

        /**
         * Permet de mettre à jour les données des panneaux solaires
         * @param olCapteurs la liste des capteurs
         * @param tablePanneau la table des données des panneaux
         * @param lineChart le graphique des données des panneaux
         * 
         */    
        public void updateData(ObservableList<DataSolarPanel> olCapteurs, TableView<DataEnergy> tablePanneau, LineChart<String, Number> lineChart) {
            updatePanneaux = new Thread(() -> {      
                while (running) {
                    try {
                        Thread.sleep(5000);
                        ObservableList<DataSolarPanel> oListPanneaux = FXCollections.observableArrayList();
                        Platform.runLater(() -> {
                            this.loadPanneaux(oListPanneaux);
                            if (oListPanneaux.get(0).getEnergy().size() != olCapteurs.get(0).getEnergy().size()) {
                                olCapteurs.clear();
                                lineChart.getData().clear();
                                olCapteurs.addAll(oListPanneaux);
                                this.loadData(tablePanneau, oListPanneaux);
                                this.loadLineChart(lineChart, oListPanneaux);
                            }

                        });
                        
                    } catch (InterruptedException e) {
                        e.printStackTrace();
                        Thread.currentThread().interrupt();
                    }
                }
                
            });
            updatePanneaux.start();
        }

        /**
         * Permet d'afficher la fenêtre de gestion des panneaux solaires
         */
        void doPanneauxDialog(){
    		this.panneauxViewController.showDialog();
        }


    /**
     * Permet de charger les données des panneaux solaires dans la table
     * @param tablePanneau la table des données des panneaux
     * @param oListPanneaux la liste des panneaux solaires
     */
    public void loadData(TableView<DataEnergy> tablePanneau, ObservableList<DataSolarPanel> oListPanneaux) {
        tablePanneau.getItems().clear();
        for (DataSolarPanel dataSolarPanel : oListPanneaux) {
            for (DataEnergy dataEnergy : dataSolarPanel.getEnergy()) {
                DataEnergy dataEnergyVal = new DataEnergy(dataEnergy.getDate(), dataEnergy.getValue());
                tablePanneau.getItems().add(dataEnergyVal);
            }
            
        }
    }


    /**
     * Permet de charger les données des panneaux solaires dans le graphique
     * @param lineChart le graphique des données des panneaux
     * @param oListPanneaux la liste des panneaux solaires
     */
    public void loadLineChart(LineChart<String, Number> lineChart, ObservableList<DataSolarPanel> oListPanneaux) {
        XYChart.Series<String, Number> series = new XYChart.Series<>();
        for (DataSolarPanel dataSolarPanel : oListPanneaux) {
            for (DataEnergy dataEnergy : dataSolarPanel.getEnergy()) {
                series.getData().add(new XYChart.Data<String, Number>(dataEnergy.getDate(), dataEnergy.getValue()));
            }
        }
        lineChart.getData().add(series);

        for (XYChart.Data<String, Number> dataSolarPanel : series.getData()) {
            dataSolarPanel.getNode().setOnMouseEntered(e -> {
                dataSolarPanel.getNode().setStyle("-fx-background-color: #B22222;");
                Tooltip.install(dataSolarPanel.getNode(), new Tooltip("Energie : " + dataSolarPanel.getYValue() +"\nDate : " + dataSolarPanel.getXValue()));

            });
            dataSolarPanel.getNode().setOnMouseExited(e -> {
                dataSolarPanel.getNode().setStyle("");

            });
            
        }
     }

    /**
     * Permet de charger les panneaux solaires
     * @param olCapteurs la liste des capteurs
     */
	public void loadPanneaux(ObservableList<DataSolarPanel> olCapteurs){
        olCapteurs.clear();
        DataLoader dataLoader = new DataLoader();
        String fileName = this.read("configTopic", "nomFichierDonnees");
        dataLoader.LoadDatasFromJson(fileName+".json");
        List<DataSolarPanel> capteurs = dataLoader.getDataSolarPanel();  
        olCapteurs.addAll(capteurs);
    }

    /**
     * Permet de lire les données du fichier de configuration
     * @param section la section du fichier de configuration
     * @param option l'option du fichier de configuration
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

}
