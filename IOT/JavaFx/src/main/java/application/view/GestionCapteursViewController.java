package application.view;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

import javafx.fxml.FXML;
import javafx.scene.chart.NumberAxis;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.NumberAxis;
import javafx.scene.chart.XYChart;
import javafx.scene.control.CheckBox;
import javafx.scene.control.ListCell;
import javafx.scene.control.ListView;
import javafx.scene.control.SelectionMode;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.layout.GridPane;
import javafx.scene.shape.Line;
import javafx.stage.Stage;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import application.control.GestionCapteurs;
import application.loader.DataLoader;
import application.loader.capteursSalle.DataCapteurs;
import application.loader.capteursSalle.DataValue;
import application.model.Donnees;

public class GestionCapteursViewController {
    private Stage containingStage;
    @SuppressWarnings("unused")
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

        this.checkCo2.setSelected(true);
        this.checkTemp.setSelected(true);
        this.checkHumidity.setSelected(true);

        listSalles.getSelectionModel().setSelectionMode(SelectionMode.MULTIPLE);
        this.listSalles.getSelectionModel().selectedItemProperty().addListener(e -> this.addDonnees());
        this.listSalles.getSelectionModel().selectedItemProperty().addListener(e -> {
            try {
                this.loadLineChart();
            } catch (ParseException e1) {

                e1.printStackTrace();
            }
        });
        
    }

    private void loadCapteurs(ObservableList<DataCapteurs> olCapteurs){
        olCapteurs.clear();
        DataLoader dataLoader = new DataLoader();
        dataLoader.LoadDatasFromJson("dataNormal.json");
        List<DataCapteurs> capteurs = dataLoader.getDataLoader();
        olCapteurs.addAll(capteurs);
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

    private void loadLineChart() throws ParseException{
        this.gridPane.getChildren().clear();
        ArrayList<DataCapteurs> capteursSelect = new ArrayList<DataCapteurs>(this.listSalles.getSelectionModel().getSelectedItems());
        
        NumberAxis xAxisC02 = new NumberAxis();
        NumberAxis yAxisC02 = new NumberAxis();
        xAxisC02.setLabel("Date");
        yAxisC02.setLabel("CO2");

        NumberAxis xAxisTemp = new NumberAxis();
        NumberAxis yAxisTemp = new NumberAxis();
        xAxisTemp.setLabel("Date");
        yAxisTemp.setLabel("Température");

        NumberAxis xAxisHumidity = new NumberAxis();
        NumberAxis yAxisHumidity = new NumberAxis();
        xAxisHumidity.setLabel("Date");
        yAxisHumidity.setLabel("Humidité");

        LineChart<Number, Number> lineChartC02 = new LineChart<>(xAxisC02, yAxisC02);
        this.gridPane.add(lineChartC02, 0, 0);
        LineChart<Number, Number> lineChartTemp = new LineChart<>(xAxisTemp, yAxisTemp);
        this.gridPane.add(lineChartTemp, 0, 1);
        LineChart<Number, Number> lineChartHumidity = new LineChart<>(xAxisHumidity, yAxisHumidity);
        this.gridPane.add(lineChartHumidity, 0, 2);

        // Ajouter les données aux LineCharts
        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        for (DataCapteurs capteurs : capteursSelect) {
            XYChart.Series<Number, Number> seriesC02 = new XYChart.Series();
            XYChart.Series<Number, Number> seriesTemp = new XYChart.Series();
            XYChart.Series<Number, Number> seriesHumidity = new XYChart.Series();

            for (DataValue dataValue : capteurs.getCo2()) {
                Date date = dateFormat.parse(dataValue.getDate());
                seriesC02.getData().add(new XYChart.Data(date.getTime(), dataValue.getValue()));

            }
            for (DataValue dataValue : capteurs.gettemp()) {
                Date date = dateFormat.parse(dataValue.getDate());
                seriesTemp.getData().add(new XYChart.Data(date.getTime(), dataValue.getValue()));

            }
            for (DataValue dataValue : capteurs.gethumidity()) {
                Date date = dateFormat.parse(dataValue.getDate());
                seriesHumidity.getData().add(new XYChart.Data(date.getTime(), dataValue.getValue()));
            }

            lineChartC02.getData().add(seriesC02);
            lineChartTemp.getData().add(seriesTemp);
            lineChartHumidity.getData().add(seriesHumidity);
        }

    }


}


