package application.view;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.scene.chart.LineChart;

import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;

import javafx.stage.Stage;


import application.control.GestionPanneaux;

import application.loader.solarPanels.DataEnergy;
import application.loader.solarPanels.DataSolarPanel;


public class GestionPanneauxViewController {
    private Stage containingStage;
    private GestionPanneaux rockPanneaux;

    private ObservableList<DataSolarPanel> oListPanneaux;

    @FXML
    TableView<DataEnergy> tablePanneau;

    @FXML
    TableColumn<DataEnergy, Double> colValeur;

    @FXML
    TableColumn<DataEnergy, String> colDate;

    @FXML
    LineChart<String, Number> lineChart;


    /**
     * Initialiser le contexte de la fenêtre de gestion des panneaux
     * @param containingStage la fenêtre parente
     * @param _rc le controlleur 
     */
    public void initContext(Stage containingStage, GestionPanneaux _rc){
        this.containingStage = containingStage;
        this.rockPanneaux = _rc;
        this.configure();
    }

    /**
     * Afficher la fenêtre de gestion des panneaux
     */
    public void showDialog(){
        this.containingStage.showAndWait();
    }

    /**
     * Configurer la fenêtre de gestion des panneaux
     */
    private void configure(){
        this.oListPanneaux = FXCollections.observableArrayList();
        this.rockPanneaux.loadPanneaux(oListPanneaux);
        rockPanneaux.loadData(this.tablePanneau, oListPanneaux);

        colDate.setCellValueFactory(new PropertyValueFactory<>("date"));
        colValeur.setCellValueFactory(new PropertyValueFactory<>("value"));

        this.rockPanneaux.updateData(this.oListPanneaux, this.tablePanneau, this.lineChart);
        colDate.setStyle( "-fx-alignment: CENTER; -fx-font-size: 16px;");
        colValeur.setStyle( "-fx-alignment: CENTER;-fx-font-size: 16px;");

        this.rockPanneaux.loadLineChart(this.lineChart, oListPanneaux);

        tablePanneau.setColumnResizePolicy(TableView.CONSTRAINED_RESIZE_POLICY);


    }

    @FXML
    public void doQuitter(){
        this.containingStage.close();
    }




}

