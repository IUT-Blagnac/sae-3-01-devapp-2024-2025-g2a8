package application.view;

import javafx.stage.FileChooser;
import javafx.stage.Stage;
import javafx.fxml.FXML;
import javafx.scene.control.Button;
import javafx.scene.control.TextField;
import javafx.scene.control.Alert.AlertType;

import java.io.File;

import application.control.ConfigCapteurs;
import application.control.GestionCapteurs;
import application.tools.AlertUtilities;

public class ConfigCapteursViewController {
    private Stage containingStage;
    private ConfigCapteurs configCapteurs;
    @FXML TextField frequence;
    @FXML Button fichPathButton;
    @FXML TextField seuil;

    public void initContext(Stage containingStage, ConfigCapteurs _rc){
        this.containingStage = containingStage;
        this.configCapteurs = _rc;
        this.frequence.setText(configCapteurs.read("configTopic", "frequence"));
        this.seuil.setText(configCapteurs.read("configTopic", "seuilTemperature"));
    }

    public void showDialog(){
        this.containingStage.showAndWait();
    }

    public void doSave(){
        configCapteurs.write(this.fichPath(), frequence.getText(), seuil.getText());
        this.containingStage.close();
    }

    @FXML
    private String fichPath(){
        FileChooser fileChooser = new FileChooser();

        // Définir les extensions acceptées (.ini)
        FileChooser.ExtensionFilter iniFilter = new FileChooser.ExtensionFilter("Fichiers INI", "*.ini");
        fileChooser.getExtensionFilters().add(iniFilter);

        // Ouvrir le FileChooser et récupérer le fichier sélectionné
        File selectedFile = fileChooser.showOpenDialog(containingStage);

        // Vérifier si un fichier a été sélectionné
        if (selectedFile != null) {
            // Afficher une alerte avec le chemin du fichier sélectionné
            AlertUtilities.showAlert(this.containingStage, "Fichier sélectionné", "Fichier Sélectionné", "Le fichier sélectionné est : " + selectedFile.getAbsolutePath(), AlertType.INFORMATION);
        } else {
            AlertUtilities.showAlert(this.containingStage, "Fichier sélectionné", "Fichié sSélectionné", "Le fichier sélectionné n'a pas été trouvé", AlertType.INFORMATION);
        }

        return selectedFile.getAbsolutePath();
    }

    @FXML
    public void doQuitter(){
        this.containingStage.close();
    }
}