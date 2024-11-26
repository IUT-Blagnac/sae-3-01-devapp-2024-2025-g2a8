package net.g2a8.iot.view;

import javafx.stage.Stage;
import net.g2a8.iot.control.GestionPanneaux;
import net.g2a8.iot.control.GestionPanneaux;

public class GestionPanneauxViewController {
        private Stage containingStage;
        private GestionPanneaux rockPanneaux;


    public void initContext(Stage containingStage, GestionPanneaux _rc){
        this.containingStage = containingStage;
        this.rockPanneaux = _rc;
    }

    public void showDialog(){
        this.containingStage.showAndWait();
    }
}

