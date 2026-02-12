package com.example.notification.controller;

import com.example.notification.service.NotificationService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Qualifier;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/notify")
public class NotificationController {

    private final NotificationService notificationService;

    @Autowired
    public NotificationController(
            @Qualifier("emailService") NotificationService notificationService) {
        this.notificationService = notificationService;
    }

    @GetMapping("/send/{message}")
    public String send(@PathVariable String message) {
        return notificationService.sendNotification(message);
    }
}
