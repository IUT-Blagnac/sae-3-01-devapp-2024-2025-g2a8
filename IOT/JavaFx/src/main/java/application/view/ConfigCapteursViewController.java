package application.view;

import javafx.stage.Stage;
import javafx.fxml.FXML;
import javafx.scene.control.TextField;
import application.control.ConfigCapteurs;
import application.control.GestionCapteurs;

public class ConfigCapteursViewController {
    private Stage containingStage;
    private ConfigCapteurs configCapteurs;
    @FXML TextField frequence;
    @FXML TextField nomFich;
    @FXML TextField seuil;

    public void initContext(Stage containingStage, ConfigCapteurs _rc){
        this.containingStage = containingStage;
        this.configCapteurs = _rc;
        this.nomFich.setText(configCapteurs.read("configTopic", "nomFichierDonnees"));
        this.frequence.setText(configCapteurs.read("configTopic", "frequence"));
        this.seuil.setText(configCapteurs.read("configTopic", "seuilTemperature"));
    }

    public void showDialog(){
        this.containingStage.showAndWait();
    }

    public void doSave(){
        configCapteurs.write(nomFich.getText(), frequence.getText(), seuil.getText());
        this.containingStage.close();
    }
}