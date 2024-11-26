package net.g2a8.iot.control;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.layout.BorderPane;
import javafx.stage.Stage;
import net.g2a8.iot.view.RockGestMainViewController;

public class RockGestMain extends Application {
    private Stage mainStage;
    private RockGestMainViewController controller;


    
    @Override
    public void start(Stage primaryStage) throws Exception {
        FXMLLoader loader;
        Scene mainScene;
        BorderPane root;

        this.mainStage = primaryStage;

        loader = new FXMLLoader(RockGestMainViewController.class.getResource("RockGestMain.fxml"));

        root = loader.load();

        mainScene = new Scene(root);

        this.mainStage.setScene(mainScene);
        this.mainStage.setTitle("RockGest");

        this.controller = loader.getController();

        this.controller.initContext(this.mainStage);
        this.controller.showDialog();
    }

    public static void runApp(){
        launch();
    }

    public void doOpenCapteursDialog(){
        RockCapteurs rockCapteurs = new RockCapteurs(this.mainStage);
        rockCapteurs.doOpenCapteursDialog();
    }
}
