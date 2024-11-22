module net.g2a8.iot {
    requires javafx.controls;
    requires javafx.fxml;
    requires javafx.web;

    requires org.controlsfx.controls;
    requires com.dlsc.formsfx;
    requires net.synedra.validatorfx;
    requires org.kordamp.bootstrapfx.core;
    requires eu.hansolo.tilesfx;

    opens net.g2a8.iot to javafx.fxml;
    exports net.g2a8.iot;
    exports net.g2a8.iot.controller;
    exports net.g2a8.iot.view;
    opens net.g2a8.iot.controller to javafx.fxml;
}