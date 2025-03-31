# **Plant Identification System**

## Python version: 3.10.10

## django version: 5.1.6

## Activate the virtual venv

## cd path/to/plant-info-system

## python -m venv venv # Create virtual environment

## source venv/bin/activate # Activate on macOS/Linux

## venv\Scripts\activate # Activate on Windows

## pip install -r requirements.txt

## To run the backend

## cd path/to/plant_backend

## python manage.py import_plants

## python manage.py makemigrations

## python manage.py migrate

## python manage.py runserver

## Run frontend

## cd path/to/php_frontend

## php -S localhost:8001

## **Introduction**

The Plant Identification System is a web-based application that identifies plants from uploaded images using AI and provides detailed information about the identified plant. It is designed for botanists, students, and plant enthusiasts.

## **System Overview**

- **Purpose**: Identify plants from images and provide detailed information.
- **Key Features**:
  - Upload plant images in JPG or PNG format.
  - AI-based plant identification with confidence levels.
  - Display detailed plant information from the database.

## **Architecture**

- **Frontend**: PHP-based web interface.
- **Backend**: Django REST API for AI-based plant identification.
- **Database**: SQLite/MySQL for storing plant information.
- **AI Framework**: TensorFlow for image processing.

_(Include a system architecture diagram here.)_

## **Technologies Used**

- **Frontend**: PHP, Bootstrap, JavaScript
- **Backend**: Django REST Framework
- **Database**: SQLite/MySQL
- **AI Framework**: TensorFlow
- **Hosting**: Local server or cloud platform

## **Features**

1. **User Authentication**: Secure login system.
2. **Image Upload**: Upload images for identification.
3. **AI Processing**: Identify plants using AI.
4. **Result Display**: Show top match and confidence levels.
5. **Database Integration**: Fetch and display plant details.

## **Database Design**

### **Plants Table**

    plant_name = models.CharField(max_length=255)
    type = models.CharField(max_length=255)
    leaves = models.TextField()
    flowers = models.TextField()
    fruits = models.TextField()
    growth = models.TextField()
    uses = models.TextField()
    image_filename = models.CharField(max_length=255)
    image = models.ImageField(upload_to='plants/', null=True, blank=True)

_(Include an ER diagram here.)_

## **Testing**

- **File Upload**: Validate file types and sizes.
- **API Response**: Test with various plant images.
- **UI Testing**: Ensure responsiveness and usability.

## **Future Enhancements**

- Add support for more image formats.
- Implement a mobile app version.
- Enhance AI model accuracy.
- Integrate a community feature for discussions.

## **Conclusion**

The Plant Identification System is a valuable tool for identifying plants and learning about their characteristics.

## **References**

- TensorFlow Documentation
- Django REST Framework Documentation
- Bootstrap Documentation
