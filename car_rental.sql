CREATE DATABASE car_rental;

USE car_rental;

CREATE TABLE cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    make VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    daily_rate DECIMAL(10, 2) NOT NULL,
    available BOOLEAN DEFAULT TRUE
);

CREATE TABLE rentals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    FOREIGN KEY (car_id) REFERENCES cars(id)
);

INSERT INTO cars (make, model, year, daily_rate) VALUES
('Toyota', 'Camry', 2022, 50.00),
('Honda', 'Civic', 2021, 45.00),
('Ford', 'Mustang', 2023, 75.00),
('Chevrolet', 'Malibu', 2022, 55.00);