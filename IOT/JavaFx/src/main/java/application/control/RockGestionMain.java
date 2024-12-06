package application.control;

import java.io.BufferedReader;
import java.io.File;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import com.fasterxml.jackson.databind.ObjectMapper;

import application.tools.AlertUtilities;
import application.view.RockGestionMainViewController;
import javafx.application.Application;
import javafx.application.Platform;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.control.Alert.AlertType;
import javafx.scene.layout.BorderPane;
import javafx.stage.Stage;

public class RockGestionMain extends Application {
    private Stage mainStage;
    private RockGestionMainViewController controller;

    private Map<String, List<Map<String, Object>>> ancienneValeur = new HashMap<>();
    private boolean premierLancement = true;

    private Thread pythonThread;
    private Process pythonProcess;

    private Thread alerte;
    private boolean running = true;

    
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

        this.mainStage.setOnCloseRequest(e -> {
            running = false;
            System.out.println("Arret du script Python");
            stopPythonScript();
        });

        this.alerte();

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

    public void reloadPythonScript() {
        stopPythonScript();
        launchPythonScript();
    }

    public void launchPythonScript(){
        pythonThread = new Thread(() -> {
            try {
                System.out.println("Starting Python script...");
    
                String scriptPath = new File(getClass().getProtectionDomain().getCodeSource().getLocation().toURI()).getParent() + File.separator + "TP-SAE-IoT.py";
                ProcessBuilder processBuilder = new ProcessBuilder("python", "-u", scriptPath);
                File scriptDirectory = new File(scriptPath).getParentFile();
                processBuilder.directory(scriptDirectory);
    
                processBuilder.environment().put("PYTHONENCODING", "UTF-8");
                processBuilder.redirectErrorStream(true);
    
                this.pythonProcess = processBuilder.start();
                
                try (InputStream inputStream = pythonProcess.getInputStream();
                     BufferedReader reader = new BufferedReader(new InputStreamReader(inputStream))) {
                    String line;
                    while ((line = reader.readLine()) != null) {
                        System.out.println(line);
                    }
                }
    
                int exitCode = pythonProcess.waitFor();
                System.out.println("Python script exited with code: " + exitCode);
    
                System.out.println("Python script finished.");
            } catch (Exception e) {
                if (pythonProcess != null && pythonThread != null) {
                    pythonProcess.destroy();
                    pythonThread.interrupt();
                }
                e.printStackTrace();
            }
        });
    
        pythonThread.start();
    }

    @SuppressWarnings("unchecked")
    public void alerte() {
        alerte = new Thread(() -> {
            ObjectMapper objectMapper = new ObjectMapper(); 
    
            while (running) {
                try {
                    Thread.sleep(2000);
                    String path = new File(getClass().getProtectionDomain().getCodeSource().getLocation().toURI()).getParent() + File.separator + "dataStrange.json";
                    System.out.println(path);

                    // Charger les données JSON
                    Map<String, Object> fichierJson = objectMapper.readValue(
                        new File(path),
                            Map.class
                    );
    
                    // Extraire la liste des capteurs depuis le JSON
                    List<Map<String, Object>> capteurs = (List<Map<String, Object>>) fichierJson.get("capteurs");
    
                    for (Map<String, Object> capteur : capteurs) {
                        String nomCapteur = (String) capteur.get("name"); // Nom unique du capteur
    
                        // Vérifier les nouvelles valeurs pour chaque type de données
                        for (String type : Arrays.asList("co2", "temp", "humidity")) {
                            List<Map<String, Object>> nouvellesValeurs = (List<Map<String, Object>>) capteur.get(type);
    
                            // Si c'est le premier lancement, initialiser les anciennes valeurs et passer au prochain type
                            if (premierLancement) {
                                ancienneValeur.putIfAbsent(nomCapteur + "-" + type, new ArrayList<>(nouvellesValeurs));
                                continue;
                            }
    
                            // S'assurer que la clé existe dans le map des anciennes valeurs
                            ancienneValeur.putIfAbsent(nomCapteur + "-" + type, new ArrayList<>());
    
                            // Récupérer les anciennes valeurs associées à ce capteur et type
                            List<Map<String, Object>> anciennesValeurs = ancienneValeur.get(nomCapteur + "-" + type);
    
                            // Si de nouvelles valeurs ont été ajoutées depuis la dernière vérification
                            if (nouvellesValeurs.size() > anciennesValeurs.size()) {
                                String val = nouvellesValeurs.get(nouvellesValeurs.size() - 1).get("value").toString();

                                System.out.println("Alerte : Nouvelle valeur ajoutée pour " + nomCapteur + " (" + type + ")");
                                System.out.println("Nouvelle valeur : " + val);
                                
                                // Afficher une alerte
                                Platform.runLater(() -> {
                                    AlertUtilities.showAlert(mainStage, "Alerte : " + type, "Dépassement de " + nomCapteur, type + " : " + val, AlertType.WARNING);
                                });
                            }
    
                            // Mettre à jour les anciennes valeurs avec la liste actuelle
                            ancienneValeur.put(nomCapteur + "-" + type, new ArrayList<>(nouvellesValeurs));
                        }
                    }
    
                    premierLancement = false;
    
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        });
    
        alerte.start();
    }
    
    
    public void stopPythonScript(){
        if (pythonProcess != null && pythonThread.isAlive()) {
            pythonProcess.destroy();
        }
        if (pythonThread != null && pythonThread.isAlive()) {
            pythonThread.interrupt();
        }
        if (alerte != null && alerte.isAlive()) {
            alerte.interrupt();
        }
    }
    
}
