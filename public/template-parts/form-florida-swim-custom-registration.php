<form id="fscrForm" class="fscrForm--registration" method="post">

  <div class="fscrForm__page active" data-fscr-page="1">

    <fieldset class="fscrForm__fieldset">

      <legend class="fscrForm__title">
        Your Contact Information
      </legend>

      <div class="fscrForm__input-wrap fscrForm__input-wrap--input-half">
        <label class="fscrForm__input-container">
          <span class="fscrForm__label">First Name <span class="field--required">*</span></span>
          <span class="fwcr__form__error" v-cloak>{{ guest.errors.first_name || validator.first('first_name') }}</span>
          <input name="first_name" type="text" v-model="guest.firstName" v-validate="'required'">
        </label>
      </div>

      <div class="fscrForm__input-wrap fscrForm__input-wrap--input-half">
        <label class="fscrForm__input-container">
          <span class="fscrForm__label">Last Name <span class="field--required">*</span></span>
          <span class="fwcr__form__error" v-cloak>{{ guest.errors.last_name || validator.first('last_name') }}</span>
          <input name="last_name" type="text" v-model="guest.lastName" v-validate="'required'">
        </label>
      </div>

      <div class="fscrForm__input-wrap fscrForm__input-wrap--input-half">
        <label class="fscrForm__input-container">
          <span class="fscrForm__label">Email Address <span class="field--required">*</span></span>
          <span class="fwcr__form__error" v-cloak>{{ guest.errors.email_address || validator.first('email') }}</span>
          <input name="email" type="email" v-model="guest.email" v-validate="'required|email'">
        </label>
      </div>

      <div class="fscrForm__input-wrap fscrForm__input-wrap--input-half">
        <label class="fscrForm__input-container">
          <span class="fscrForm__label">Phone Number <span class="field--required">*</span></span>
          <span class="fwcr__form__error" v-cloak>{{ guest.errors.phone_number || validator.first('phone') }}</span>
          <input name="phone" type="tel" v-model="guest.phone" v-validate="'required'">
        </label>
      </div>

      <div class="fscrForm__input-wrap fscrForm__input-wrap--input-half">
        <label class="fscrForm__input-container">
          <span class="fscrForm__label">ZIP Code <span class="field--required">*</span></span>
          <span class="fwcr__form__error" v-cloak>{{ guest.errors.zip_code || validator.first('zip_code') }}</span>
          <input name="zip_code" type="tel" v-model="guest.zipCode" v-validate="'required'">
        </label>
      </div>

    </fieldset>

    <fieldset class="fscrForm__fieldset">
      <legend class="fscrForm__label">Do you have access to a pool? <span class="field--required">*</span></legend>
      <span class="fwcr__form__error" v-cloak>{{ guest.errors.pool_access || validator.first('pool_access') }}</span>
      <label class="d-inline-block m-0">
        <div class="fscrForm__custom-radio--square" v-bind:class="{ active: guest.poolAccess == 'true' }" tabindex="0" @keyup.enter="updatePoolAccess(true)">
          Yes
        </div>
        <input name="pool_access" type="radio" value="true" v-model="guest.poolAccess" class="hidden" v-validate="'required'">
      </label>
      <label class="d-inline-block m-0">
        <div class="fscrForm__custom-radio--square" v-bind:class="{ active: guest.poolAccess == 'false' }" tabindex="0" @keyup.enter="updatePoolAccess(false)">
          No
        </div>
        <input name="pool_access" type="radio" value="false" v-model="guest.poolAccess" class="hidden" v-validate="'required'">
      </label>
    </fieldset>

    <hr>

    <div class="fscrForm__btn-container">
      <button type="button" v-on:click="handleFirstPageSubmission" class="fscr__button fscr__button--primary">Next</button>
    </div>

  </div>


  <div class="fscrForm__page" data-fscr-page="2">

    <fieldset class="fscrForm__fieldset">

      <legend class="fscrForm__title">
        Student Information
      </legend>

      <div class="fscrForm__input-wrap fscrForm__input-wrap--input-half">
        <div class="fscrForm__input-container">
          <label>
            <span class="fscrForm__label">How many students are you enrolling? <span class="field--required">*</span></span>
            <span class="fwcr__form__error" v-cloak>{{ students.errors.number_students_enrolling || validator.first('number_students_enrolling') }}</span>
            <select name="number_students_enrolling" v-model.number="students.count" @change="changeAmountOfStudents" class="fscrForm__input" v-validate="'required|min_value:1'">
              <option selected="selected" value="0">Please Select</option>
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
          <div class="fscrForm__input-wrap fscrForm__input-wrap--input-half">
            <div class="fscrForm__input-container">
              <label>
                <span class="d-block">Student {{ n }} Name <span class="field--required">*</span></span>
                <span class="fwcr__form__error" v-cloak>{{ validator.first('student_name_' + (n) ) }}</span>
                <input type="text" v-model="students.students[n-1].name" :name="'student_name_' + (n)" v-validate="'required'">
              </label>
            </div>
          </div>
          <div class="fscrForm__input-wrap fscrForm__input-wrap--input-half">
            <div class="fscrForm__input-container">
              <label :for="'student__dob--' + n">
                <span class="d-block">Student {{ n }} DOB <span class="field--required">*</span></span>
              </label>
              <datepicker :id="'student__dob--' + n" @input="updateStudentDob($event, n-1)" v-model="students.students[n-1].dobOriginal"></datepicker>
            </div>
          </div>
        </div>
      </div>


      <div class="fscrForm__input-wrap fscrForm__input-wrap--input-half">
        <label class="fscrForm__input-container">
          <span class="d-block">Questions or information we should know about the student(s)? (i.e. special needs, medical issues, goals, etc.)</span>
          <span class="fwcr__form__error" v-cloak></span>
          <textarea name="student_additional_info" rows="5"></textarea>
        </label>
      </div>

    </fieldset>

    <fieldset class="fscrForm__fieldset">
      <legend class="fscrForm__label">
        Are you the parent/guardian of all the sudents? <span class="field--required">*</span>
        <span class="fwcr__form__error" v-cloak>{{ validator.first('parent_guardian') }}</span>
      </legend>
      <label class="fscrForm__label">
        <span class="fscrForm__custom-radio fscrForm__custom-radio--checkbox" tabindex="0">
          <span class="fscrForm__label__text">Yes</span>
          <input name="parent_guardian" type="radio" value="true" @change="confirmParentGuardianForAll" checked v-validate="'required'">
          <span class="fscrForm__checkmark"></span>
        </span>
      </label>
      <label class="fscrForm__label">
        <span class="fscrForm__custom-radio fscrForm__custom-radio--checkbox" tabindex="0">
          <span class="fscrForm__label__text">No</span>
          <input name="parent_guardian" type="radio" value="false" @change="confirmParentGuardianForAll" v-validate="'required'">
          <span class="fscrForm__checkmark"></span>
        </span>
      </label>
    </fieldset>

    <fieldset v-if="confirmParentGuardians" class="fscrForm__fieldset">
      <legend class="fscrForm__label">Please confirm the parent/guardian for each student.</legend>
      <span class="fwcr__form__error" v-cloak></span>
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
              <span class="d-block">First Name</span>
              <input type="text" v-model="parents.parents[n-1].first_name">
            </label>
            <label>
              <span class="d-block">Last Name</span>
              <input type="text" v-model="parents.parents[n-1].last_name">
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

    <hr>

    <div class="fscrForm__btn-container space-between">
      <button type="button" v-on:click="goBack" class="fscr__button fscr__button--primary">Back</button>
      <button type="button" v-on:click="" class="fscr__button fscr__button--primary">Next</button>
    </div>

  </div>

  <div class="fscrForm__page" data-fscr-page="3">

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

  </div>

</form>