package net.g2a8.iot.view;

import javafx.fxml.FXML;
import javafx.scene.control.Button;
import javafx.stage.Stage;
import net.g2a8.iot.control.RockGestMain;

public class RockGestMainViewController {
    private Stage containingStage;

    private RockGestMain rockGestMain;


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


    @FXML
    private Button buttDonnees;

    @FXML
    public void doOpenCapteursDialog(){
        this.rockGestMain.doOpenCapteursDialog();
    } 


}
