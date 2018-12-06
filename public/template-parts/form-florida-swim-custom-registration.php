<form id="fscrForm" class="form--registration" method="post">

    <fieldset class="fieldset fieldset--contact">

      <legend>
        Your Contact Information
      </legend>

      <div class="input-wrap input--half">
        <label class="input-container">
          <span class="d-block">First Name <span class="field--required">*</span></span>
          <span class="fwcr__form__error">{{ errors.first_name }}</span>
          <input name="first_name" type="text" v-model="registrant.firstName">
        </label>
      </div>

      <div class="input-wrap input--half">
        <label class="input-container">
          <span class="d-block">Last Name <span class="field--required">*</span></span>
          <span class="fwcr__form__error">{{ errors.last_name }}</span>
          <input name="last_name" type="text" v-model="registrant.lastName">
        </label>
      </div>

      <div class="input-wrap input--half">
        <label class="input-container">
          <span class="d-block">Email Address <span class="field--required">*</span></span>
          <span class="fwcr__form__error">{{ errors.email_address }}</span>
          <input name="email" type="email" v-model="registrant.email" v-on:input="foo">
        </label>
      </div>

      <div class="input-wrap input--half">
        <label class="input-container">
          <span class="d-block">Phone Number <span class="field--required">*</span></span>
          <span class="fwcr__form__error">{{ errors.phone_number }}</span>
          <input name="phone" type="tel" v-model="registrant.phone">
        </label>
      </div>

      <div class="input-wrap input--half">
        <label class="input-container">
          <span class="d-block">ZIP Code <span class="field--required">*</span></span>
          <span class="fwcr__form__error">{{ errors.zip_code }}</span>
          <input name="zip" type="tel" v-model="registrant.zipCode">
        </label>
      </div>

    </fieldset>

    <fieldset class="fieldset fieldset--pool-access">
        <legend class="label">Do you have access to a pool? <span class="field--required">*</span></legend>
        <span class="fwcr__form__error">{{ errors.pool_access }}</span>
        <label class="d-inline-block">
          <span class="d-block custom-radio custom-radio--square">
            <span class="d-block">Yes</span>
            <input name="pool_access" type="radio" value="true" v-model="registrant.poolAccess">
          </span>
        </label>
        <label class="d-inline-block">
          <span class="d-inline-block custom-radio custom-radio--square">
            <span class="d-block">No</span>
            <input name="pool_access" type="radio" value="false" v-model="registrant.poolAccess">
          </span>
        </label>
      </fieldset>

    <button type="button" v-on:click="createRegistrant">Next</button>

    <fieldset class="fieldset fieldset--student-info">

      <legend>
        Student Information
      </legend>

      <div class="input-wrap input--half">
        <div class="input-container">
          <label>
            <span class="d-block">How many students are you enrolling? <span class="field--required">*</span></span>
            <select name="number_students_enrolling" v-model.number="students.count" @change="changeAmountOfStudents">
              <option value="0" default selected>Please Select</option>
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
        <div class="input--student" v-for="n in students.count">
          <div class="input-wrap input--half">
            <div class="input-container">
              <label>
                <span class="d-block">Student {{ n }} Name <span class="field--required">*</span></span>
                <input type="text" v-model="students.students[n-1].name">
                <span>{{ students.students[n-1].name }}</span>
              </label>
            </div>
          </div>
          <div class="input-wrap input--half">
            <div class="input-container">
              <label :for="'student__dob--' + n">
                <span class="d-block">Student {{ n }} DOB <span class="field--required">*</span></span>
              </label>
              <datepicker :id="'student__dob--' + n" @input="updateStudentDob($event, n-1)" v-model="students.students[n-1].dobOriginal"></datepicker>
            </div>
          </div>
        </div>
      </div>


      <div class="input-wrap">
        <label>
          <span class="d-block">Questions or information we should know about the student(s)? (i.e. special needs, medical issues, goals, etc.)</span>
          <textarea name="student_additional_info"></textarea>
        </label>
      </div>

    </fieldset>

    <fieldset class="fieldset">
      <legend class="label">
        Are you the parent/guardian of all the sudents? <span class="field--required">*</span>
      </legend>
      <label class="d-inline-block">
        <span class="d-block custom-radio custom-radio--square" tabindex="0">
          <span class="d-block">Yes</span>
          <input name="parent_guardian" type="radio" value="true" @change="confirmParentGuardianForAll">
        </span>
      </label>
      <label class="d-inline-block">
        <span class="d-block custom-radio custom-radio--square" tabindex="0">
          <span class="d-block">No</span>
          <input name="parent_guardian" type="radio" value="false" @change="confirmParentGuardianForAll">
        </span>
      </label>
    </fieldset>

    <fieldset v-if="confirmParentGuardians">
      <legend>Please confirm the parent/guardian for each student.</legend>
      <button type="button" @click="addParentGuardianField">Add</button>
      <button type="button" @click="removeParentGuardianField">Remove</button>
      <div class="parent-guardians">
        <div class="parent-guardian" v-for="n in Number(parents.count)">
          <fieldset>
            <legend>Parent/Guardian Information</legend>
            <fieldset>
              <legend>Parent/Guardian of</legend>
              <label v-for="x in students.count" class="d-inline-block">
                <input type="checkbox" :value="students.students[x-1].id" v-model="parents.parents[n-1].students">
                <span>{{ students.students[x-1].name }}</span>
              </label>
            </fieldset>
            <label>
              <span class="d-block">Name</span>
              <input type="text" v-model="parents.parents[n-1].name">
            </label>
            <label>
              <span class="d-block">Email Address</span>
              <input type="email" v-model="parents.parents[n-1].email">
            </label>
            <label>
              <span class="d-block">Phone Number</span>
              <input type="tel" v-model="parents.parents[n-1].phone">
            </label>
          </fieldset>
        </div>
      </div>
    </fieldset>

    <button type="button" v-on:click="createStudentsAndGuardians">Next</button>

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