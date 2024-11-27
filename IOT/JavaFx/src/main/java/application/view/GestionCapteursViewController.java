package application.view;

import javafx.stage.Stage;
import application.control.GestionCapteurs;

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
