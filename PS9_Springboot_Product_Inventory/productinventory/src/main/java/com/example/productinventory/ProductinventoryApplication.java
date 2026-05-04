package com.example.productinventory;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.data.mongodb.repository.config.EnableMongoRepositories;

@SpringBootApplication
@EnableMongoRepositories
public class ProductinventoryApplication {

	public static void main(String[] args) {
		SpringApplication.run(ProductinventoryApplication.class, args);
	}

}
