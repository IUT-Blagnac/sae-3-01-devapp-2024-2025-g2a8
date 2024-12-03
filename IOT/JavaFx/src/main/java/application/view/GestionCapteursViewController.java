package application.view;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

import javafx.fxml.FXML;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.XYChart;
import javafx.scene.control.CheckBox;
import javafx.scene.control.ListView;
import javafx.scene.control.SelectionMode;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.layout.GridPane;
import javafx.stage.Stage;


import java.util.ArrayList;

import application.control.GestionCapteurs;
import application.loader.capteursSalle.DataCapteurs;
import application.loader.capteursSalle.DataValue;
import application.model.Donnees;

public class GestionCapteursViewController {
    private Stage containingStage;
    private GestionCapteurs rockCapteurs;
    // Données de la fenêtre
	private ObservableList<DataCapteurs> oListCapteurs;
  
    @FXML 
    ListView<DataCapteurs> listSalles;

    @FXML
    TableView<Donnees> tableCapteurs;

    @FXML
    TableColumn<Donnees, String> colType;

    @FXML
    TableColumn<Donnees, String> colDate;

    @FXML
    TableColumn<Donnees, Double> colValeur;

    @FXML
    TableColumn<Donnees, String> colSalle;

    @FXML
    CheckBox checkCo2;

    @FXML
    CheckBox checkTemp;

    @FXML
    CheckBox checkHumidity;


    @FXML
    GridPane gridPane;
    


    public void initContext(Stage containingStage, GestionCapteurs _rc){
        this.containingStage = containingStage;
        this.rockCapteurs = _rc;
        this.configure();
    }

    public void showDialog(){
        this.containingStage.showAndWait();
    }

    private void configure(){
        
        this.oListCapteurs = FXCollections.observableArrayList();
        this.rockCapteurs.loadCapteurs(oListCapteurs);
        this.listSalles.setItems(this.oListCapteurs);
        this.listSalles.setAccessibleText(this.oListCapteurs.toString());


        colDate.setCellValueFactory(new PropertyValueFactory<>("date"));
        colValeur.setCellValueFactory(new PropertyValueFactory<>("valeur"));
        colType.setCellValueFactory(new PropertyValueFactory<>("type"));
        colSalle.setCellValueFactory(new PropertyValueFactory<>("salle"));

        this.checkCo2.setSelected(true);
        this.checkTemp.setSelected(true);
        this.checkHumidity.setSelected(true);

        listSalles.getSelectionModel().setSelectionMode(SelectionMode.MULTIPLE);
        this.listSalles.getSelectionModel().selectedItemProperty().addListener(e -> this.addDonnees());
        this.listSalles.getSelectionModel().selectedItemProperty().addListener(e -> this.loadLineChart());

        this.checkCo2.selectedProperty().addListener(e -> this.loadLineChart());
        this.checkTemp.selectedProperty().addListener(e -> this.loadLineChart());
        this.checkHumidity.selectedProperty().addListener(e -> this.loadLineChart());
        
        
        
    }


    @FXML
    private void addDonnees(){
        ArrayList<DataCapteurs> capteursSelect = new ArrayList<DataCapteurs>(this.listSalles.getSelectionModel().getSelectedItems());
        
        this.tableCapteurs.getItems().clear();
        for (DataCapteurs capteurs : capteursSelect) {
            if (this.checkCo2.isSelected()) {
                for (DataValue dataValue : capteurs.getCo2()) {
                    Donnees donnee = new Donnees(dataValue.getDate(), dataValue.getValue(), "PPM", capteurs.getname());
                    this.tableCapteurs.getItems().add(donnee);
                }
            }
            if (this.checkTemp.isSelected()) {
                for (DataValue dataValue : capteurs.gettemp()) {
                    Donnees donnee = new Donnees(dataValue.getDate(), dataValue.getValue(), "°C", capteurs.getname());
                    this.tableCapteurs.getItems().add(donnee);
                }
            }
            if (this.checkHumidity.isSelected()) {
                for (DataValue dataValue : capteurs.gethumidity()) {
                    Donnees donnee = new Donnees(dataValue.getDate(), dataValue.getValue(), "%", capteurs.getname());
                    this.tableCapteurs.getItems().add(donnee);
                }
            }

        }
    }

    private void loadLineChart() {

        this.gridPane.getChildren().clear();
        ArrayList<DataCapteurs> capteursSelect = new ArrayList<DataCapteurs>(this.listSalles.getSelectionModel().getSelectedItems());

        //ou se trouve le graphique
        int rowIndex = 0;
        int rowIndex2 = 0;

        //decalaration Des graphiques généraux
        

        //decalaration graphique C02 générale
        LineChart<String, Number> lineChartC02 = this.rockCapteurs.loadLineChart("PPM", "C02");

        //decalaration graphique Humidité générale
        LineChart<String, Number> lineChartHumidity = this.rockCapteurs.loadLineChart("%", "Humidité");

        //decalaration graphique Température générale
        LineChart<String, Number> lineChartTemp = this.rockCapteurs.loadLineChart("°C", "Température");


        //placement des graphiques
        if (this.checkCo2.isSelected()) {
            this.gridPane.add(lineChartC02, 0, rowIndex2);
            rowIndex2++;
        }
        if (this.checkHumidity.isSelected()) {
            this.gridPane.add(lineChartHumidity, 0, rowIndex2);
            rowIndex2++;
        }
        if (this.checkTemp.isSelected()) {
            this.gridPane.add(lineChartTemp, 0, rowIndex2);
            rowIndex2++;
        }


        for (DataCapteurs capteurs : capteursSelect) {
            //data a mettre dans les graphiques 
            XYChart.Series<String, Number> seriesC02 = new XYChart.Series<>();
            XYChart.Series<String, Number> seriesC02Seul = new XYChart.Series<>();

            XYChart.Series<String, Number> seriesHumidity = new XYChart.Series<>();
            XYChart.Series<String, Number> seriesHumiditySeul = new XYChart.Series<>();

            XYChart.Series<String, Number> seriesTemp = new XYChart.Series<>();
            XYChart.Series<String, Number> seriesTempSeul = new XYChart.Series<>();
            if(this.checkCo2.isSelected()) {

                lineChartC02.setVisible(true);
                //graphique co2 seul par salle
                LineChart<String, Number> lineChartC02Seul = this.rockCapteurs.loadLineChart("PPM", "C02 : "+capteurs.getname());
                this.gridPane.add(lineChartC02Seul, 1, rowIndex);
                rowIndex++;
                for (DataValue dataValue : capteurs.getCo2()) {
                    //ajout des données dans les series
                    seriesC02.getData().add(new XYChart.Data<>(dataValue.getDate(), dataValue.getValue()));
                    seriesC02Seul.getData().add(new XYChart.Data<>(dataValue.getDate(), dataValue.getValue()));
                }

                seriesC02.setName(capteurs.getname());
                lineChartC02.getData().add(seriesC02);
                lineChartC02Seul.getData().add(seriesC02Seul);
            }
            if(this.checkHumidity.isSelected()){
                lineChartHumidity.setVisible(true);
                //graphique humidité seul par salle
                LineChart<String, Number> lineChartHumiditySeul = this.rockCapteurs.loadLineChart("%", "Humidité : "+capteurs.getname());
                this.gridPane.add(lineChartHumiditySeul, 1, rowIndex);
                rowIndex++;

                

                for (DataValue dataValue : capteurs.gethumidity()) {
                    //ajout des données dans les series
                    seriesHumidity.getData().add(new XYChart.Data<>(dataValue.getDate(), dataValue.getValue()));
                    seriesHumiditySeul.getData().add(new XYChart.Data<>(dataValue.getDate(), dataValue.getValue()));
                }

                seriesHumidity.setName(capteurs.getname());
                lineChartHumidity.getData().add(seriesHumidity);
                lineChartHumiditySeul.getData().add(seriesHumiditySeul);

            }

            if (this.checkTemp.isSelected()) {
                lineChartTemp.setVisible(true);
                //graphique température seul par salle
                LineChart<String, Number> lineChartTempSeul = this.rockCapteurs.loadLineChart("°C", "Température : "+capteurs.getname());
                this.gridPane.add(lineChartTempSeul, 1, rowIndex);
                rowIndex++;
                
                
                for (DataValue dataValue : capteurs.gettemp()) {
                    //ajout des données dans les series
                    seriesTemp.getData().add(new XYChart.Data<>(dataValue.getDate(), dataValue.getValue()));
                    seriesTempSeul.getData().add(new XYChart.Data<>(dataValue.getDate(), dataValue.getValue()));

                }

                seriesTemp.setName(capteurs.getname());
                lineChartTemp.getData().add(seriesTemp);
                lineChartTempSeul.getData().add(seriesTempSeul);

            }
        }

    }
    


}


