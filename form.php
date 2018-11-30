<!doctype html>
<html lang="en-us">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Registration Form</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="main.css" />
</head>

<body>

  <form class="form--registration" method="post" action="processor.php">

    <fieldset class="fieldset fieldset--contact">

      <legend>
        Your Contact Information
      </legend>

      <div class="input-wrap input--half">
        <label class="input-container">
          <span class="d-block">First Name <span class="field--required">*</span></span>
          <input name="first_name" type="text">
        </label>
      </div>

      <div class="input-wrap input--half">
        <label class="input-container">
          <span class="d-block">Last Name <span class="field--required">*</span></span>
          <input name="last_name" type="text">
        </label>
      </div>

      <div class="input-wrap input--half">
        <label class="input-container">
          <span class="d-block">Email Address <span class="field--required">*</span></span>
          <input name="email" type="email">
        </label>
      </div>

      <div class="input-wrap input--half">
        <label class="input-container">
          <span class="d-block">Phone Number <span class="field--required">*</span></span>
          <input name="phone" type="tel">
        </label>
      </div>

      <div class="input-wrap input--half">
        <label class="input-container">
          <span class="d-block">ZIP Code <span class="field--required">*</span></span>
          <input name="zip" type="tel">
        </label>
      </div>

      <fieldset class="fieldset fieldset--pool-access">
        <legend class="label">Do you have access to a pool? <span class="field--required">*</span></legend>
        <label class="d-inline-block">
          <span class="d-block custom-radio custom-radio--square" tabindex="0">
            <span class="d-block">Yes</span>
            <input name="pool_access" type="radio" value="true">
          </span>
        </label>
        <label class="d-inline-block">
          <span class="d-inline-block custom-radio custom-radio--square" tabindex="0">
            <span class="d-block">No</span>
            <input name="pool_access" type="radio" value="false">
          </span>
        </label>
      </fieldset>

    </fieldset>

    <fieldset class="fieldset fieldset--student-info">

      <legend>
        Student Information
      </legend>

      <div class="input-wrap input--half">
        <div class="input-container">
          <label>
            <span class="d-block">How many students are you enrolling? <span class="field--required">*</span></span>
            <select name="number_students_enrolling">
              <option value="">Please Select</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
            </select>
          </label>
        </div>
      </div>

      <br>

      <div class="students-container">
        <div class="input--student">
          <div class="input-wrap input--half">
            <div class="input-container">
              <label>
                <span class="d-block">Student <span class="student-number"></span> Name <span class="field--required">*</span></span>
                <input type="text">
              </label>
            </div>
          </div>
          <div class="input-wrap input--half">
            <div class="input-container">
              <label>
                <span class="d-block">Student <span class="student-number"></span> DOB <span class="field--required">*</span></span>
                <input type="text">
              </label>
            </div>
          </div>
        </div>
        <input type="hidden" name="student_information">
      </div>

      <div class="input-wrap">
        <label>
          <span class="d-block">Questions or information we should know about the student(s)? (i.e. special needs, medical issues, goals, etc.)</span>
          <textarea name="student_additional_info"></textarea>
        </label>
      </div>

      <fieldset class="fieldset">
        <legend class="label">
          Are you the parent/guardian of all the sudents? <span class="field--required">*</span>
        </legend>
        <label class="d-inline-block">
          <span class="d-block custom-radio custom-radio--square" tabindex="0">
            <span class="d-block">Yes</span>
            <input name="parent_guardian" type="radio" value="true">
          </span>
        </label>
        <label class="d-inline-block">
          <span class="d-block custom-radio custom-radio--square" tabindex="0">
            <span class="d-block">No</span>
            <input name="parent_guardian" type="radio" value="false">
          </span>
        </label>
      </fieldset>

    </fieldset>

    <fieldset class="fieldset">

      <legend>
        Swim Lesson Package Selection
      </legend>

      <fieldset class="fieldset fieldset--lesson-type">
        <legend class="label">Lesson Type <span class="field--required">*</span></legend>
        <label class="d-inline-block">
          <span class="d-block custom-radio custom-radio--square-2">
            <span class="d-block lesson-type__title">Swimming Lessons</span>
            <span class="d-block lesson-type__cta">Select</span>
            <input name="lesson_type" type="radio" value="Swimming Lessons">
          </span>
        </label>
        <label class="d-inline-block">
          <span class="d-block custom-radio custom-radio--square-2">
            <span class="d-block lesson-type__title">Water Aerobics</span>
            <span class="d-block lesson-type__cta">Select</span>
            <input name="lesson_type" type="radio" value="Water Aerobics">
          </span>
        </label>
      </fieldset>

      <fieldset class="fieldset fieldset--lesson-duration">
        <legend class="label">Lesson Duration <span class="field--required">*</span></legend>
        <label class="d-inline-block">
          <span class="d-block custom-radio custom-radio--square-2">
            <span class="d-block lesson-type__title">30 Minutes</span>
            <span class="d-block lesson-type__cta">Select</span>
            <input name="lesson_duration" type="radio" value="30 Minutes">
          </span>
        </label>
        <label class="d-inline-block">
          <span class="d-block custom-radio custom-radio--square-2">
            <span class="d-block lesson-type__title">60 Minutes</span>
            <span class="d-block lesson-type__cta">Select</span>
            <input name="lesson_duration" type="radio" value="60 Minutes">
          </span>
        </label>
      </fieldset>

      <fieldset class="fieldset fieldset--lesson-duration">
        <legend class="label">Lesson Package <span class="field--required">*</span></legend>
        <label class="d-inline-block">
          <span class="d-block custom-radio custom-radio--square-2">
            <span class="d-block lesson-type__title">Learn to Swim</span>
            <span class="d-block lesson-type__cta">Select</span>
            <input name="lesson_package" type="radio" value="">
          </span>
        </label>
        <label class="d-inline-block">
          <span class="d-block custom-radio custom-radio--square-2">
            <span class="d-block lesson-type__title">Water Aerobics</span>
            <span class="d-block lesson-type__cta">Select</span>
            <input name="lesson_package" type="radio" value="60 Minutes">
          </span>
        </label>
      </fieldset>

    </fieldset>

    <input type="submit" value="submit">

  </form>

  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  
</body>
</html>



