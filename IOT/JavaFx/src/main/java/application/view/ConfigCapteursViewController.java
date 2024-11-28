package application.view;

import javafx.stage.Stage;
import application.control.ConfigCapteurs;
import application.control.GestionCapteurs;

public class ConfigCapteursViewController {
    private Stage containingStage;
    private ConfigCapteurs configCapteurs;


    public void initContext(Stage containingStage, ConfigCapteurs _rc){
        this.containingStage = containingStage;
        this.configCapteurs = _rc;
    }

    public void showDialog(){
        this.containingStage.showAndWait();
    }
}
