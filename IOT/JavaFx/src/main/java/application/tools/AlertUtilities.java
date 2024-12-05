package application.tools;

import java.util.Optional;

import javafx.scene.control.Alert;
import javafx.scene.control.Alert.AlertType;
import javafx.scene.control.ButtonType;
import javafx.stage.Stage;

/**
 * Utilitaire pour afficher une fenêtre de message ou de confirmation.
 *
 */

public class AlertUtilities {

	/**
	 * Affiche une message de confirmation d'un message avec bouton Ok.
	 *
	 * @param _fen     Fenêtre (Stage) sur laquelle le dialogue se centre et est
	 *                 modal.
	 * @param _title   Titre du dialogue
	 * @param _message Message à confirmer
	 * @param _content Détail d'information
	 * @param _at      Type d'alerte (icône associé) (constante définie par
	 *                 AlertType)
	 * @return true si dialogue confirmé, false sinon
	 */
	public static boolean confirmYesCancel(Stage _fen, String _title, String _message, String _content, AlertType _at) {

		if (_at == null) {
			_at = AlertType.INFORMATION;
		}
		Alert alert = new Alert(_at);
		alert.initOwner(_fen);
		alert.setTitle(_title);
		if (_message == null || !_message.equals(""))
			alert.setHeaderText(_message);
		alert.setContentText(_content);

		Optional<ButtonType> option = alert.showAndWait();
		if (option.isPresent() && option.get() == ButtonType.OK) {
			return true;
		}
		return false;
	}

	/**
	 * Affiche un message de confirmation avec boutons OUI/NON
	 * 
	 * @author SERRE Lohan
	 * 
	 * @param _fen     Fenêtre (Stage) sur laquelle le dialogue se centre et est
	 *                 modal.
	 * @param _title   Titre du dialogue
	 * @param _message Message à confirmer
	 * @param _content Détail d'information
	 * @param _at      Type d'alerte (icône associé) (constante définie par
	 *                 AlertType)
	 * @return true si dialogue confirmé, false sinon
	 */
	public static boolean confirmYesNo(Stage _fen, String _title, String _message, String _content, AlertType _at) {
		if (_at == null) {
			_at = AlertType.CONFIRMATION;
		}
		Alert alert = new Alert(_at);
		alert.initOwner(_fen);
		alert.setTitle(_title);
		if (_message != null && !_message.equals("")) {
			alert.setHeaderText(_message);
		}
		alert.setContentText(_content);
	
		alert.getButtonTypes().setAll(ButtonType.YES, ButtonType.NO);
		Optional<ButtonType> option = alert.showAndWait();
		return option.isPresent() && option.get() == ButtonType.YES;
	}

	/**
	 * Affiche une message simple avec bouton de fermeture.
	 *
	 * @param _fen     Fenêtre (Stage) sur laquelle le dialogue se centre et est
	 *                 modal.
	 * @param _title   Titre du dialogue
	 * @param _message Message à donner
	 * @param _content Détail d'information
	 * @param _at      Type d'alerte (icône associé) (constante définie par
	 *                 AlertType)
	 */
	public static void showAlert(Stage _fen, String _title, String _message, String _content, AlertType _at) {

		if (_at == null) {
			_at = AlertType.INFORMATION;
		}
		Alert alert = new Alert(_at);
		alert.initOwner(_fen);
		alert.setTitle(_title);
		if (_message == null || !_message.equals(""))
			alert.setHeaderText(_message);
		alert.setContentText(_content);

		alert.showAndWait();
	}
}
