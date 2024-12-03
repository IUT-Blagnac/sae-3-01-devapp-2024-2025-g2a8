package application.control;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.layout.BorderPane;
import javafx.stage.Stage;

import java.io.BufferedReader;
import java.io.File;
import java.io.IOException;
import java.io.InputStream;

import application.view.RockGestionMainViewController;
import java.io.InputStreamReader;

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
        Thread thread = new Thread(() -> {
            try {
                System.out.println("Starting Python script...");
    
                String scriptPath = "C:\\Users\\Etudiant\\Downloads\\sae-3-01-devapp-2024-2025-g2a8\\IOT\\Systeme\\TP-SAE-IoT.py";
                ProcessBuilder processBuilder = new ProcessBuilder("python", scriptPath);
                File scriptDirectory = new File(scriptPath).getParentFile();
                processBuilder.directory(scriptDirectory);
                Process process = processBuilder.start();
                
                try (InputStream inputStream = process.getInputStream();
                     BufferedReader reader = new BufferedReader(new InputStreamReader(inputStream))) {
                    String line;
                    while ((line = reader.readLine()) != null) {
                        System.out.println(line);
                    }
                }
    
                int exitCode = process.waitFor();
                System.out.println("Python script exited with code: " + exitCode);
    
                System.out.println("Python script finished.");
            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    
        thread.start();
    }
    
}
