package com.example.demo.controller;

import com.example.demo.service.HelloService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api")
public class HelloController {

    // Field-level Dependency Injection
    @Autowired
    private HelloService helloService;

    @GetMapping("/hello")
    public String hello() {
        return helloService.sayHello();
    }

    @GetMapping("/greet/{name}")
    public String greet(@PathVariable String name) {
        return helloService.greetUser(name);
    }
}
