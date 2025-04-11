-- Create Classes Table
CREATE TABLE Classes (
    ClassID INT PRIMARY KEY AUTO_INCREMENT,
    ClassType VARCHAR(50) NOT NULL,
    ClassName VARCHAR(50) UNIQUE NOT NULL,
    ClassCapacity INT NOT NULL,
    ClassNotes TEXT
);

-- Create Guardian Table
CREATE TABLE Guardian (
    GuardianID INT PRIMARY KEY AUTO_INCREMENT,
    GuardianType VARCHAR(50),
    Title VARCHAR(10),
    FirstName VARCHAR(50) NOT NULL,
    Surname VARCHAR(50) NOT NULL,
    Address_Line1 VARCHAR(100),
    Address_Line2 VARCHAR(100),
    Address_Line3 VARCHAR(100),
    Postcode VARCHAR(10),
    ContactNumber VARCHAR(15),
    EmailAddress VARCHAR(100),
    Notes TEXT
);

-- Create Teachers Table
CREATE TABLE Teachers (
    TeacherID INT PRIMARY KEY AUTO_INCREMENT,
    ClassID INT UNIQUE,
    Title VARCHAR(10),
    FirstName VARCHAR(50) NOT NULL,
    Surname VARCHAR(50) NOT NULL,
    Address_Line1 VARCHAR(100),
    Address_Line2 VARCHAR(100),
    Address_Line3 VARCHAR(100),
    Postcode VARCHAR(10),
    ContactNumber VARCHAR(15),
    EmailAddress VARCHAR(100),
    Salary DECIMAL(10,2),
    Notes TEXT,
    FOREIGN KEY (ClassID) REFERENCES Classes(ClassID)
);

-- Create Pupils Table
CREATE TABLE Pupils (
    PupilID INT PRIMARY KEY AUTO_INCREMENT,
    Title VARCHAR(10),
    FirstName VARCHAR(50) NOT NULL,
    Surname VARCHAR(50) NOT NULL,
    DOB DATE,
    Address_Line1 VARCHAR(100),
    Address_Line2 VARCHAR(100),
    Address_Line3 VARCHAR(100),
    Postcode VARCHAR(10),
    Medical_Allergy TEXT,
    Medical_Vaccination TEXT,
    ClassID INT,
    Guardian1ID INT,
    Guardian2ID INT,
    Notes TEXT,
    FOREIGN KEY (ClassID) REFERENCES Classes(ClassID),
    FOREIGN KEY (Guardian1ID) REFERENCES Guardian(GuardianID),
    FOREIGN KEY (Guardian2ID) REFERENCES Guardian(GuardianID)
);

-- Create Books Table
CREATE TABLE Books (
    BookID INT PRIMARY KEY AUTO_INCREMENT,
    BookCategory VARCHAR(50),
    BookTitle VARCHAR(100),
    LoanedOut ENUM('Y', 'N'),
    BookedBy INT,
    Notes TEXT
    FOREIGN KEY (BookedBy) REFERENCES Pupils(PupilID)
);

-- Create Attendance Table
CREATE TABLE Attendance (
    AttendanceID INT PRIMARY KEY AUTO_INCREMENT,
    PupilID INT,
    ClassID INT,
    AttendanceDate DATE,
    Status ENUM('Present', 'Absent'),
    Notes TEXT,
    FOREIGN KEY (PupilID) REFERENCES Pupils(PupilID),
    FOREIGN KEY (ClassID) REFERENCES Classes(ClassID)
);

CREATE TABLE administrators (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
