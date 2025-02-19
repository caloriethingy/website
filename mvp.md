# MVP Plan: Food Image Analysis App (Yii2)

**I. Overall Goal:**

Develop a functional proof-of-concept mobile application for food image analysis. The app allows users to create
accounts, upload images of food, and receive basic nutritional summaries.

**II. Core Features:**

1. **User Authentication:**
    * User registration (email, password).
    * User login (email, password).
    * Secure JWT-based authentication for API access.

2. **Image Uploading:**
    * Ability for users to capture images using the device camera or select images from the device's photo library.
    * Background uploading to the Yii2 API.

3. **Image Analysis:**
    * The Yii2 API will call the Google Gemini API for image analysis.
    * Data extracted by the Gemini API includes calories, protein, fat, carbs, and fiber. A basic, fast local analysis
      will be implemented to give a result even when Gemini is unavailable or offline.

4. **Meal Tracking:**
    * Display of a daily meal summary in the mobile app, showing the nutritional information for the user's uploaded
      meals.

5. **Data Storage:**
    * Store user accounts and meal data in a MySQL database.

**III. Technology Stack:**

* **Backend:**
    * Yii2 (PHP framework)
    * MySQL (Database)
* **Frontend:**
    * Kotlin (Android) *or* Swift (iOS)  — You'll need separate implementations for each platform.

**IV. Development Plan**

**A. Yii2 API (Backend):**

1. **Project Setup:**
    * Install Yii2 and choose a suitable project structure template.

2. **Database Models:**
    * Create `User` and `Meal` models (classes that represent data in the database).

3. **Controllers:**
    * Create controllers (`AuthController`, `MealController`):
        * `AuthController` will handle user registration and login, generating JWT tokens.
        * `MealController` will:
            * `createMeal()`: Receive the image, save it, and then call Gemini.
            * `getDailySummary()`: Query database for a user's daily meal summaries.

4. **API Endpoints:**
    * Define API endpoints (using Yii2's RESTful capabilities) for these tasks.

5. **Security:**
    * Implement secure password hashing (e.g., `password_hash()`).
    * Use JWTs to protect API routes.

6. **Image Handling:**
    * Configure a file storage system to securely save uploaded images.
    * Implement image processing.
    * Implement error handling for image processing and uploading issues.

7. **Gemini Integration:**
    * Implement the necessary code to call Gemini's API, handling potential errors.
    * Implement the local analysis

8. **Testing:**
    * Develop thorough unit tests, and integration tests for all API endpoints using `yii2-codeception` or a similar
      tool.

**B. Kotlin/Swift App (Frontend - Split into iOS/Android):**

1. **Project Setup:** Create separate Android and iOS projects in their respective IDEs.
2. **API Integration:** Implement the necessary code to communicate with the Yii2 API (`GET`, `POST` requests).
3. **User Interface:** Design user interfaces (UI) in native layouts for each platform.
4. **Security:**
    * Handle JWTs securely and appropriately (e.g., using `SharedPreferences`).
    * Implement secure data handling and validation.
5. **Image Handling:**  Implement the UI for selecting or taking photos. Use libraries for displaying the images.
6. **Image Uploading:**
    * Implement image upload handling using `WorkManager` on Android (or a similar background task approach on iOS).
7. **Data Handling:**
    * Implement ViewModels and data binding in the respective apps.
    * Handle the API responses from the Laravel API to display the nutritional information.

**C. Testing and Deployment:**

* Thorough testing of both the API and the mobile applications on separate environments.
* Deployment to a testing or staging server.

**VI. Data Model Notes:**

* Keep your data models simple and well-structured in the database to improve performance and readability.

**VII.Important Considerations**

* **Data Privacy:** Ensure compliance with any relevant data privacy regulations (e.g., GDPR, CCPA).
* **Error Handling:** Implement robust error handling for both backend and frontend to handle various scenarios.
* **Security Best Practices:** Adhere to all security best practices.

This plan lays out the core MVP development phases. You'll need to elaborate on specific technologies, libraries, and
design decisions as you progress. Remember to prioritize functionality for this proof of concept over extensive design.
For example, consider using an initial mock for the Gemini API response, and if/when it works use the correct data to
update the data models.