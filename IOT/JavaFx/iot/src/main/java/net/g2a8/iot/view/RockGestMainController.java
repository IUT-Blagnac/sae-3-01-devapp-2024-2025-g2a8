package net.g2a8.iot.view;

import javafx.fxml.FXML;
import javafx.stage.Stage;

public class RockGestMainController {
    private Stage containingStage;

    public void initContext(Stage containingStage){
        this.containingStage = containingStage;
    }

    public void showDialog(){
        this.containingStage.show();
    }

    @FXML
    public void doQuitter(){
        this.containingStage.close();
    }
}
