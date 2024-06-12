CREATE DATABASE IF NOT EXISTS clinic;
USE clinic;

CREATE TABLE Patients (
    patient_id VARCHAR(20) PRIMARY KEY,
    patient_name VARCHAR(255) NOT NULL,
    patient_username VARCHAR(255) UNIQUE,
    patient_email VARCHAR(255) NOT NULL,
    patient_password VARCHAR(255) NOT NULL, 
    patient_gender VARCHAR(10),
    patient_birth_date DATE,
    patient_age INT,
    patient_phone VARCHAR(20),
    patient_address VARCHAR(255),
    INDEX (patient_username)  
);

CREATE TABLE Clinics (
    clinic_id INT AUTO_INCREMENT PRIMARY KEY,
    clinic_name VARCHAR(255) NOT NULL,
    clinic_password VARCHAR(255) NOT NULL,  
    clinic_open_time VARCHAR(50),  
    clinic_phone VARCHAR(20) NOT NULL,
    clinic_address VARCHAR(255),
    clinic_department VARCHAR(100)
);

CREATE TABLE Doctors (
    doctor_id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_name VARCHAR(255) NOT NULL,
    doctor_gender VARCHAR(10),
    doctor_profession VARCHAR(100),
    doctor_password VARCHAR(255) NOT NULL, 
    doctor_rating INT,
    clinic_id INT,
    FOREIGN KEY (clinic_id) REFERENCES Clinics(clinic_id)
);

CREATE TABLE Appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_patient_id VARCHAR(20),
    appointment_doctor_id INT,
    appointment_clinic_id INT,
    appointment_time TIMESTAMP,
    appointment_symptoms TEXT,
    FOREIGN KEY (appointment_patient_id) REFERENCES Patients(patient_id),
    FOREIGN KEY (appointment_doctor_id) REFERENCES Doctors(doctor_id),
    FOREIGN KEY (appointment_clinic_id) REFERENCES Clinics(clinic_id)
);

CREATE TABLE Reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    review_patient_id VARCHAR(20),
    review_doctor_id INT,
    review_clinic_id INT,
    review_rating DECIMAL(3,1),
    review_comment TEXT,
    FOREIGN KEY (review_patient_id) REFERENCES Patients(patient_id),
    FOREIGN KEY (review_doctor_id) REFERENCES Doctors(doctor_id),
    FOREIGN KEY (review_clinic_id) REFERENCES Clinics(clinic_id)
);

INSERT INTO Patients (patient_id, patient_name, patient_username, patient_email, patient_password, patient_gender, patient_birth_date, patient_age, patient_phone, patient_address) VALUES
('F111111111', 'John Doe', 'john_doe', 'john.doe@example.com', 'hashedpassword1', 'Male', '1980-01-01', 40, '1234567890', '123 Street, City'),
('F222222222', 'Jane Smith', 'jane_smith', 'jane.smith@example.com', 'hashedpassword2', 'Female', '1992-02-02', 28, '0987654321', '456 Avenue, City');

INSERT INTO Clinics (clinic_name, clinic_password, clinic_open_time, clinic_phone, clinic_address, clinic_department) VALUES
('Happy Clinic', 'clinicpass1', '08:00-17:00', '555-1234', '123 Happy St, Happy City', 'Pediatrics'),
('Health Clinic', 'clinicpass2', '09:00-18:00', '555-5678', '456 Health Ave, Health Town', 'Dermatology');

INSERT INTO Doctors (doctor_name, doctor_gender, doctor_profession, doctor_password, doctor_rating, clinic_id) VALUES
('Dr. Alice Johnson', 'Female', 'Pediatrician', 'hashedpassword3', 5, 1),
('Dr. Bob Smith', 'Male', 'Dermatologist', 'hashedpassword4', 4, 2);

INSERT INTO Appointments (appointment_patient_id, appointment_doctor_id, appointment_clinic_id, appointment_time, appointment_symptoms) VALUES
('F111111111', 1, 1, '2023-06-15 09:30:00', 'Cough and fever'),
('F222222222', 2, 2, '2023-06-16 14:00:00', 'Skin rash');

INSERT INTO Reviews (review_patient_id, review_doctor_id, review_clinic_id, review_rating, review_comment) VALUES
('F111111111', 1, 1, 5.0, 'Excellent care and friendly staff.'),
('F222222222', 2, 2, 4.0, 'Good experience, but a bit of a wait.');
