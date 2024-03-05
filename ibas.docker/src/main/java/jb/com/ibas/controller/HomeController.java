package jb.com.ibas.controller;

import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.servlet.config.annotation.WebMvcConfigurer;

@RestController
public class HomeController implements WebMvcConfigurer {

    @GetMapping("/")
    public String handleError() {
        // Your custom error handling logic goes here
        return "/greeting"; // Return the name of your custom error page
    }

    @GetMapping("/home")
    public String home() {
        return "/error";
    }

}
