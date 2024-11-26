package net.g2a8.iot.view;

import javafx.stage.Stage;

public class GestionCapteursViewController {
    private Stage containingStage;


    public void initContext(Stage containingStage){
        this.containingStage = containingStage;
    }

    public void showDialog(){
        this.containingStage.showAndWait();
    }
}
