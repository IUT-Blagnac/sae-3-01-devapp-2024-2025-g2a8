package net.g2a8.iot.view;

import javafx.stage.Stage;
import net.g2a8.iot.control.GestionCapteurs;

public class GestionCapteursViewController {
    private Stage containingStage;
    private GestionCapteurs rockCapteurs;


    public void initContext(Stage containingStage, GestionCapteurs _rc){
        this.containingStage = containingStage;
        this.rockCapteurs = _rc;
    }

    public void showDialog(){
        this.containingStage.showAndWait();
    }
}
