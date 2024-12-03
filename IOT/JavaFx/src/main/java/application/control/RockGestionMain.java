package application.control;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.layout.BorderPane;
import javafx.stage.Stage;

import java.io.IOException;

import application.view.RockGestionMainViewController;

public class RockGestionMain extends Application {
    private Stage mainStage;
    private RockGestionMainViewController controller;


    
    @Override
    public void start(Stage primaryStage) throws Exception {
        FXMLLoader loader;
        Scene mainScene;
        BorderPane root;

        launchPythonScript();

        this.mainStage = primaryStage;

        loader = new FXMLLoader(RockGestionMainViewController.class.getResource("RockGestMain.fxml"));

        root = loader.load();

        mainScene = new Scene(root);

        this.mainStage.setScene(mainScene);
        this.mainStage.setTitle("RockWorld Gestion");

        this.controller = loader.getController();

        this.controller.initContext(this.mainStage, this);
        this.controller.showDialog();
    }

    public static void runApp(){
        launch();
    }

    public void capteurs(){
        GestionCapteurs gestionCapteurs = new GestionCapteurs(this.mainStage);
        gestionCapteurs.doCapteursDialog();
    }

    public void panneaux() {
        GestionPanneaux gestionPanneaux = new GestionPanneaux(this.mainStage);
        gestionPanneaux.doPanneauxDialog();
    }

    public void openConfig() {
        ConfigCapteurs configCapteurs = new ConfigCapteurs(this.mainStage);
        configCapteurs.doConfigDialog();
    }

    public void launchPythonScript(){
       try {
            System.out.println("Lancement du script Python");
            // Chemin du script Python
            String pythonPath = getClass().getResource("/TP-SAE-IoT.py").getPath();

            // Lancer le script Python sans capturer la sortie
            Process process = Runtime.getRuntime().exec("python " + pythonPath);

            // Optionnel : Assure-toi de nettoyer les flux (important pour éviter des blocages)
            process.getInputStream().close();
            process.getErrorStream().close();
            process.getOutputStream().close();

            System.out.println("Script Python lancé");
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
    
}
