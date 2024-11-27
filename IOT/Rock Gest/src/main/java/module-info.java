module net.g2a8.rockgest {
    requires javafx.controls;
    requires javafx.fxml;
    requires javafx.web;

    requires org.controlsfx.controls;
    requires com.dlsc.formsfx;
    requires net.synedra.validatorfx;
    requires org.kordamp.ikonli.javafx;
    requires org.kordamp.bootstrapfx.core;
    requires eu.hansolo.tilesfx;
    requires com.almasb.fxgl.all;

    opens net.g2a8.rockgest to javafx.fxml;
    exports net.g2a8.rockgest;
    exports net.g2a8.rockgest.control;
    exports net.g2a8.rockgest.view;
    opens net.g2a8.rockgest.view to javafx.fxml;
}