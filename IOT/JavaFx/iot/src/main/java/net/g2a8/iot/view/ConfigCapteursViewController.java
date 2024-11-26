package net.g2a8.iot.view;

import javafx.stage.Stage;
import net.g2a8.iot.control.ConfigCapteurs;
import net.g2a8.iot.control.GestionCapteurs;

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
