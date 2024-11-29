package application.view;

import javafx.beans.property.SimpleStringProperty;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.scene.control.CheckBox;
import javafx.scene.control.ListCell;
import javafx.scene.control.ListView;
import javafx.scene.control.SelectionMode;
import javafx.scene.control.Tab;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.stage.Stage;

import java.util.ArrayList;
import java.util.List;
import java.util.stream.Collectors;

import application.control.GestionCapteurs;
import application.loader.DataLoader;
import application.loader.capteursSalle.DataCapteurs;
import application.loader.capteursSalle.DataValue;
import application.model.Donnees;

public class GestionCapteursViewController {
    private Stage containingStage;
    private GestionCapteurs rockCapteurs;
    // Données de la fenêtre
	private ObservableList<DataCapteurs> oListCapteurs;
  

    


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
        
        this.loadCapteurs(oListCapteurs);
        this.listSalles.setItems(this.oListCapteurs);
      // Définir le CellFactory personnalisé
        this.listSalles.setCellFactory(param -> new ListCell<DataCapteurs>() {
            @Override
            protected void updateItem(DataCapteurs capteur, boolean empty) {
                super.updateItem(capteur, empty);
                if (empty || capteur == null) {
                    setText(null);
                } else {
                    setText(capteur.getname()); // Affiche uniquement le nom de la salle
                }
            }


        }
        );

        colDate.setCellValueFactory(new PropertyValueFactory<>("date"));
        colValeur.setCellValueFactory(new PropertyValueFactory<>("valeur"));
        colType.setCellValueFactory(new PropertyValueFactory<>("type"));
        colSalle.setCellValueFactory(new PropertyValueFactory<>("salle"));

        this.tableCapteurs.setColumnResizePolicy(TableView.CONSTRAINED_RESIZE_POLICY);


        listSalles.getSelectionModel().setSelectionMode(SelectionMode.MULTIPLE);
        this.listSalles.getSelectionModel().selectedItemProperty().addListener(e -> this.addDonnees());
    }

    private void loadCapteurs(ObservableList<DataCapteurs> olCapteurs){
        DataLoader dataLoader = new DataLoader();
        dataLoader.LoadDatasFromJson("dataNormal.json");
        List<DataCapteurs> capteurs = dataLoader.getDataLoader();
        olCapteurs.addAll(capteurs);
    }



    @FXML 
    ListView<DataCapteurs> listSalles;

    @FXML
    TableView<Donnees> tableCapteurs;

    @FXML
    TableColumn<Donnees, String> colType;

    @FXML
    TableColumn<Donnees, String> colDate;

    @FXML
    TableColumn<Donnees, String> colValeur;

    @FXML
    TableColumn<Donnees, String> colSalle;

    @FXML
    CheckBox checkCo2;

    @FXML
    CheckBox checkTemp;

    @FXML
    CheckBox checkHumidity;

    @FXML
    private void addDonnees(){
        ArrayList<DataCapteurs> capteursSelect = new ArrayList<DataCapteurs>(this.listSalles.getSelectionModel().getSelectedItems());
        this.tableCapteurs.getItems().clear();
        for (DataCapteurs capteurs : capteursSelect) {
            if (this.checkCo2.isSelected()) {
                for (DataValue dataValue : capteurs.getCo2()) {
                    Donnees donnee = new Donnees(dataValue.getDate(), String.valueOf(dataValue.getValue()), "PPM", capteurs.getname());
                    this.tableCapteurs.getItems().add(donnee);
                }
            }
            if (this.checkTemp.isSelected()) {
                for (DataValue dataValue : capteurs.gettemp()) {
                    Donnees donnee = new Donnees(dataValue.getDate(), String.valueOf(dataValue.getValue()), "°C", capteurs.getname());
                    this.tableCapteurs.getItems().add(donnee);
                }
            }
            if (this.checkHumidity.isSelected()) {
                for (DataValue dataValue : capteurs.gethumidity()) {
                    Donnees donnee = new Donnees(dataValue.getDate(), String.valueOf(dataValue.getValue()), "%", capteurs.getname());
                    this.tableCapteurs.getItems().add(donnee);
                }
            }

            
        }
    }


}


