package net.g2a8.rockgest.view;

import javafx.fxml.FXML;
import javafx.scene.control.Button;
import javafx.stage.Stage;
import net.g2a8.rockgest.control.RockGestionMain;

public class RockGestionMainViewController {
    private Stage containingStage;

    private RockGestionMain rockGestMain;


    public void initContext(Stage containingStage, RockGestionMain _rgm){
        this.containingStage = containingStage;
        this.rockGestMain = _rgm;
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
    private Button butPanneaux;


    @FXML
    public void doCapteurs(){
        this.rockGestMain.capteurs();
    } 

    @FXML
    public void doPanneaux(){
        this.rockGestMain.panneaux();
    } 
    
    @FXML
    public void doConfig(){
        this.rockGestMain.openConfig();
    } 

}
