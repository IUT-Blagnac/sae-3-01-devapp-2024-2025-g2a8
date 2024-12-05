package application.view;

import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

import javafx.fxml.FXML;
import javafx.scene.chart.LineChart;
import javafx.scene.control.CheckBox;
import javafx.scene.control.ListView;
import javafx.scene.control.SelectionMode;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.layout.GridPane;
import javafx.stage.Stage;


import java.util.ArrayList;
import java.util.List;

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

    

    /**
     * Initialise le contexte de la fenêtre de gestion des capteurs.
     * Configure la fenêtre et l'associe au contrôleur de gestion des capteurs.
     * 
     * @param containingStage La fenêtre principale qui contiendra la vue
     * @param _rc L'instance de GestionCapteurs qui gère la logique des capteurs
     */
    public void initContext(Stage containingStage, GestionCapteurs _rc){
        this.containingStage = containingStage;
        this.rockCapteurs = _rc;
        this.configure();
    }

    /**
     * Affiche la fenêtre de gestion des capteurs.
     */
    public void showDialog(){
        this.containingStage.showAndWait();
    }

    /**
     * Configure l'interface utilisateur et initialise les composants de la vue.
     * Cette méthode met en place :
     * - Les colonnes de la table des capteurs avec leurs propriétés respectives
     * - Les cases à cocher pour les différents types de capteurs (CO2, Température, Humidité)
     * - Le mode de sélection multiple pour la liste des salles
     * - Les écouteurs d'événements pour la mise à jour dynamique des données
     * - La politique de redimensionnement des colonnes de la table
     * 
     * Elle initialise également les données et met à jour l'affichage.
     */
    private void configure(){
        // Initialisation des données
        this.configureData(true);  

        // Configuration des colonnes de la table
        colDate.setCellValueFactory(new PropertyValueFactory<>("date"));
        colValeur.setCellValueFactory(new PropertyValueFactory<>("valeur"));
        colType.setCellValueFactory(new PropertyValueFactory<>("type"));
        colSalle.setCellValueFactory(new PropertyValueFactory<>("salle"));

        //Les cases coché par défaut pour les différents types de capteurs (CO2, Température, Humidité)
        this.checkCo2.setSelected(true);
        this.checkTemp.setSelected(true);
        this.checkHumidity.setSelected(true);

        //Mode de sélection multiple pour la liste des salles
        listSalles.getSelectionModel().setSelectionMode(SelectionMode.MULTIPLE);

        //Ajout des différents listeners
        this.listSalles.getSelectionModel().selectedItemProperty().addListener(e -> this.addDonnees());
        this.listSalles.getSelectionModel().selectedItemProperty().addListener(e -> this.loadLineChart());

        this.checkCo2.selectedProperty().addListener(e -> this.loadLineChart());
        this.checkTemp.selectedProperty().addListener(e -> this.loadLineChart());
        this.checkHumidity.selectedProperty().addListener(e -> this.loadLineChart());

        //Politique de redimensionnement des colonnes de la table
        this.tableCapteurs.setColumnResizePolicy(TableView.CONSTRAINED_RESIZE_POLICY);
        
        //Mise à jour dynamique des données avec l'aide d'un thread
        this.updateData();

   
    }

    /**
     * Configure les données de la vue.
     * Cette méthode initialise la liste des capteurs et les affiche dans la liste des salles.
     * Elle met également à jour les données de la table des capteurs et les graphiques.
     * 
     * @param listSallesUpdate Indique si la liste des salles doit être mise à jour
     */
    public void configureData(boolean listSallesUpdate){
        

        this.oListCapteurs = FXCollections.observableArrayList();
        this.rockCapteurs.loadCapteurs(oListCapteurs);
        if(listSallesUpdate){

            List<DataCapteurs> selectedCapteurs = this.listSalles.getSelectionModel().getSelectedItems();
            List<String> selectedCapteursName = new ArrayList<String>();
            for (DataCapteurs dataCapteurs : selectedCapteurs) {
                selectedCapteursName.add(dataCapteurs.getname());
            }

            this.listSalles.getItems().clear();
            this.listSalles.setItems(this.oListCapteurs);
            this.listSalles.setAccessibleText(this.oListCapteurs.toString());
            

            for (String dataCapteursName : selectedCapteursName) {
                for (DataCapteurs dataCapteurs : this.oListCapteurs) {
                    if (dataCapteurs.getname().equals(dataCapteursName)) {
                        this.listSalles.getSelectionModel().select(dataCapteurs);
                    }
                }
            }
        }else{
            this.addDonnees();
            this.loadLineChart();
        }
    }


    /**
     * Ajoute les données des capteurs sélectionnés à la table des capteurs.
     */
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

    /**
     * Charge les graphiques des capteurs sélectionnés.
     */
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

        //ajout des séries aux graphiques généraux et création de 3 nouveaux graphiques pour chaque capteurs	
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


    /**
     * Met à jour les données de la vue.
     * Cette méthode crée un thread qui met à jour les données de la vue toutes les 5 secondes si nécessaire.
     */
    public void updateData() {
        Thread updateCapteurs = new Thread(() -> {      
            while (true) {
                try {
                    Thread.sleep(5000);
                    
                    if(this.oListCapteurs != null){
                        ObservableList <DataCapteurs> olCapteurs = FXCollections.observableArrayList();
                        this.rockCapteurs.loadCapteurs(olCapteurs);
                        Platform.runLater(() -> {

                            if (this.oListCapteurs.size() == olCapteurs.size()) {
                                for (int i = 0; i < olCapteurs.size(); i++) {
                                    if (!this.isEqual(this.oListCapteurs.get(i), olCapteurs.get(i), "CO2")) {
                                        this.configureData(false);
                                    }
                                }    
                            } else {
                                this.configureData(true);
                            }
                        });
                    }
                    

                } catch (InterruptedException e) {
                    e.printStackTrace();
                    Thread.currentThread().interrupt(); // Restore the interrupted status
                }
            }
            
        });
        updateCapteurs.start();
    }

    /**
     * Vérifie si les données des capteurs sont égales.
     * 
     * @param capteurs Les données des capteurs
     * @param olCapteurs Les nouvelles données des capteurs
     * @param type Le type de données à comparer
     * @return true si les données sont égales, false sinon
     */
    private boolean isEqual(DataCapteurs capteurs, DataCapteurs olCapteurs, String type) {
        return capteurs.getValues(type).size() == olCapteurs.getValues(type).size();
    }

    

}


