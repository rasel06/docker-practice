package com.jbl.ibas.controller;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.servlet.config.annotation.WebMvcConfigurer;

import jakarta.servlet.http.HttpServletRequest;

@Controller
public class HomeController implements WebMvcConfigurer {

	@GetMapping("/")
	public String index(Model model, HttpServletRequest request) {
		return "home";
	}

	@GetMapping("/error")
	public String home(Model model, HttpServletRequest request) {
		return "error";
	}

}
