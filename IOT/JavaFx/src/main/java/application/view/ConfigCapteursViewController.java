package application.view;

import javafx.stage.Stage;
import javafx.fxml.FXML;
import javafx.scene.control.TextField;
import application.control.ConfigCapteurs;

public class ConfigCapteursViewController {
    private Stage containingStage;
    private ConfigCapteurs configCapteurs;

    @FXML TextField frequence;
    @FXML TextField seuilTemperature;
    @FXML TextField seuilHumidite;
    @FXML TextField seuilCO2;
    @FXML TextField nomFichierDonnees;
    @FXML TextField nomFichierDonneesStrange;


    /**
     * Initialiser le contexte de la fenêtre de configuration
     * @param containingStage la fenêtre parente
     * @param _rc le controlleur 
     */
    public void initContext(Stage containingStage, ConfigCapteurs _rc){
        this.containingStage = containingStage;
        this.configCapteurs = _rc;
        this.frequence.setText(configCapteurs.read("configTopic", "frequence"));
        this.seuilTemperature.setText(configCapteurs.read("configTopic", "seuilTemperature"));
        this.seuilHumidite.setText(configCapteurs.read("configTopic", "seuilHumidity"));
        this.seuilCO2.setText(configCapteurs.read("configTopic", "seuilCo2"));
        this.nomFichierDonnees.setText(configCapteurs.read("configTopic", "nomFichierDonnees"));
        this.nomFichierDonneesStrange.setText(configCapteurs.read("configTopic", "nomFichierDonneesStrange"));
    }

    /**
     * Afficher la fenêtre de configuration
     */
    public void showDialog(){
        this.containingStage.showAndWait();
    }

    /**
     * Sauvegarder les configurations
     */
    public void doSave(){
        configCapteurs.write(frequence.getText(), seuilTemperature.getText(), seuilHumidite.getText(), seuilCO2.getText(), nomFichierDonnees.getText(), nomFichierDonneesStrange.getText());
        this.containingStage.close();
    }

    /**
     * Quitter la fenêtre de configuration
     */
    @FXML
    public void doQuitter(){
        this.containingStage.close();
    }
}