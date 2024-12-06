package application.view;

import javafx.stage.Stage;
import javafx.fxml.FXML;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import application.control.ConfigCapteurs;

public class ConfigCapteursViewController {
    private Stage containingStage;
    private ConfigCapteurs configCapteurs;

    @FXML TextField frequence;
    @FXML TextField seuilTemperature;
    @FXML TextField seuilHumidite;
    @FXML TextField seuilCO2;
    @FXML Label fileChooser;

    public void initContext(Stage containingStage, ConfigCapteurs _rc){
        this.containingStage = containingStage;
        this.configCapteurs = _rc;
        this.frequence.setText(configCapteurs.read("configTopic", "frequence"));
        this.seuilTemperature.setText(configCapteurs.read("configTopic", "seuilTemperature"));
        this.seuilHumidite.setText(configCapteurs.read("configTopic", "seuilHumidity"));
        this.seuilCO2.setText(configCapteurs.read("configTopic", "seuilCo2"));
    }

    public void showDialog(){
        this.containingStage.showAndWait();
    }

    public void doSave(){
        configCapteurs.write(frequence.getText(), seuilTemperature.getText(), seuilHumidite.getText(), seuilCO2.getText());
        this.containingStage.close();
    }

    @FXML
    public void doQuitter(){
        this.containingStage.close();
    }
}