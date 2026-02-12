package com.example.servletapp;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.web.servlet.ServletComponentScan;

@SpringBootApplication
@ServletComponentScan
public class ServletAppApplication {

    public static void main(String[] args) {
        SpringApplication.run(ServletAppApplication.class, args);
        System.out.println("Spring Boot Servlet App Started");
    }
}
