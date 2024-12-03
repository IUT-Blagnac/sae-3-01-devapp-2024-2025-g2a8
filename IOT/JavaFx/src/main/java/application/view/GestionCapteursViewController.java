package application.view;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

import javafx.fxml.FXML;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.XYChart;
import javafx.scene.control.CheckBox;
import javafx.scene.control.Label;
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
        
        this.configureData();

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

        tableCapteurs.setColumnResizePolicy(TableView.CONSTRAINED_RESIZE_POLICY);
        
   
    }

    private void configureData(){
        this.oListCapteurs = FXCollections.observableArrayList();
        this.rockCapteurs.loadCapteurs(oListCapteurs);
        this.listSalles.setItems(this.oListCapteurs);
        this.listSalles.setAccessibleText(this.oListCapteurs.toString());
        this.addDonnees();
        this.loadLineChart();
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
        int rowIndex2 = 0;
        int rowIndex = 0;

        //decalaration graphique C02 générale
        LineChart<Number, Number> lineChartC02 = this.rockCapteurs.createLineChart("PPM", "C02");

        //decalaration graphique Humidité générale
        LineChart<Number, Number> lineChartHumidity = this.rockCapteurs.createLineChart("%", "Humidité");

        //decalaration graphique Température générale
        LineChart<Number, Number> lineChartTemp = this.rockCapteurs.createLineChart("°C", "Température");

        //placement des graphiques
        if (this.checkCo2.isSelected()) {
            this.gridPane.add(lineChartC02, 0, rowIndex2++);
        }
        if (this.checkHumidity.isSelected()) {
            this.gridPane.add(lineChartHumidity, 0, rowIndex2++);
        }
        if (this.checkTemp.isSelected()) {
            this.gridPane.add(lineChartTemp, 0, rowIndex2++);
        }

        for (DataCapteurs capteurs : capteursSelect) {
            if (this.checkCo2.isSelected()) {
                rockCapteurs.addSeriesToLineChart(capteurs, "CO2", "PPM", lineChartC02, this.gridPane, rowIndex++);
            }
            if (this.checkHumidity.isSelected()) {
                rockCapteurs.addSeriesToLineChart(capteurs, "Humidité", "%", lineChartHumidity, this.gridPane, rowIndex++);
            }
            if (this.checkTemp.isSelected()) {
                rockCapteurs.addSeriesToLineChart(capteurs, "Température", "°C", lineChartTemp, this.gridPane, rowIndex++);
            }
        }

    }

}


