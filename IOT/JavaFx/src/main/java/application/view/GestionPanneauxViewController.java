package application.view;

import javafx.stage.Stage;
import application.control.GestionPanneaux;
import application.control.GestionPanneaux;

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

