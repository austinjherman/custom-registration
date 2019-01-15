<template>

  <div id="app" class="fscr">
    
    <!-- Page 1 -->
    <fieldset class="fieldset">
      <legend>Your Contact Information</legend>
      <Guest ref="guest"/>
      <hr>
      <div class="fscrForm__btn-container">
        <button type="button" @click="handleFirstPageSubmission()" class="fscr__button fscr__button--primary">Next</button>
      </div>
    </fieldset>

    <!-- Page 2 -->
    <fieldset class="fieldset">
      <legend>Student Information</legend>
      <div class="input-wrap">
        <label>
          <span class="d-block">How many students are you enrolling? <span class="asterisk--required">*</span></span>                
          <select name="numberOfStudents" v-validate="'required|min_value:1'" v-model.number="numberOfStudents">
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
          <span class="d-block">{{ this.validator.first('numberOfStudents') }}</span>
        </label>
      </div>
      <div v-for="n in numberOfStudents" v-bind:key="n">
        <Student :vvScope="'student_' + n" :ref="'students'" />
      </div>
    </fieldset>

    <fieldset class="fieldset">
      <legend>Parent Information</legend>
      <div class="input-wrap">
        <fieldset class="fieldset">
          <legend>Are you the parent/guardian of all the students? <span class="asterisk--required">*</span></legend>
          <label>
            <span class="d-block">Yes</span>
            <input type="radio" value="true" v-model="guestIsOnlyParent">
          </label>
          <label>
            <span class="d-block">No</span>
            <input type="radio" value="false" v-model="guestIsOnlyParent">
          </label>
        </fieldset>
      </div>
      <div v-if="guestIsOnlyParent == 'false'">
        <div class="input-wrap">
          <label>
            <span class="d-block">How many parents are you signing up? <span class="asterisk--required">*</span></span>           
            <select name="numberOfParents" v-validate="'required|min_value:1'" v-model.number="numberOfParents">
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
            <span class="d-block">{{ validator.first('numberOfParents') }}</span>
          </label>
        </div>
      </div>
      <div v-if="guestIsOnlyParent == 'false'">
        <div v-for="n in numberOfParents" v-bind:key="n">
          <Parent :vvScope="'parent_' + (n-1)" ref="parents"/>
        </div>
      </div>
      <hr>
      <div class="fscrForm__btn-container">
        <button type="button" @click="goToPage(1)" class="fscr__button fscr__button--primary">Back</button>
        <button type="button" @click="handleSecondPageSubmission()" class="fscr__button fscr__button--primary">Next</button>
      </div>
    </fieldset>

    <!-- Page 3 -->
    <fieldset class="fieldset">
      <legend>Swim Lesson Package Selection</legend>
      <fieldset class="fieldset">
        <legend>How long would you like each lesson to last for your student(s)?</legend>
        <label>
          <span class="d-block">30</span>
          <input name="lessonDuration" type="radio" v-model="selectedLessonDuration" value="30" v-validate="'required'">
        </label>
        <label>
          <span class="d-block">60</span>
          <input name="lessonDuration" type="radio" v-model="selectedLessonDuration" value="60" v-validate="'required'">
        </label>
      </fieldset>
      <fieldset class="fieldset">
        <div v-for="lp in displayableLessonPackages">
          <LessonPackage :title="lp.title" :duration="lp.duration" :price="lp.price" />
        </div>
      </fieldset>
      <hr>
      <div class="fscrForm__btn-container">
        <button type="button" @click="goToPage(2)" class="fscr__button fscr__button--primary">Back</button>
        <button type="button" @click="handleThirdPageSubmission()" class="fscr__button fscr__button--primary">Next</button>
      </div>
    </fieldset>

    <!-- Page 4 -->
    <fieldset class="fieldset">
      <legend>Preferred Schedule</legend>
      <label>
        <span class="d-block">What days work for you?</span>
        <multiselect name="daysThatWork" v-model="daysThatWork" :options="['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']" :multiple="true" v-validate="'required'" :vv-validate-on="'input|close'"></multiselect>
          <span class="d-block">{{ validator.first('daysThatWork') }}</span>
      </label>
      <label>
        <span class="d-block">Select your time range availability for weekdays.</span>
        <multiselect name="weekdayTimeRangeAvailability" v-model="weekdayTimeRangeAvailability" :options="['Morning (8am - 12pm)', 'Afternoon (12pm - 4pm)', 'Evening (4pm - 8pm)']" :multiple="true" v-validate="'required'" :vv-validate-on="'input|close'"></multiselect>
        <span class="d-block">{{ validator.first('weekdayTimeRangeAvailability') }}</span>
      </label>
      <label>
        <span class="d-block">Additional scheduling information, if any.</span>
        <textarea></textarea>
      </label>
      <hr>
      <div class="fscrForm__btn-container">
        <button type="button" @click="goToPage(3)" class="fscr__button fscr__button--primary">Back</button>
        <button type="button" @click="handleFourthPageSubmission()" class="fscr__button fscr__button--primary">Next</button>
      </div>
    </fieldset>

    <!-- Page 5 -->
    <fieldset class="fieldset">
      <legend>Order Summary</legend>
      <label>
        <span class="d-block">Apply a promo code.</span>
        <input type="text" v-model="promoCode">
      </label>
      <button type="button" @click="checkPromoCode">Apply</button>
      <section>
        <h1>Lesson Package Cost Breakdown</h1>
      </section>
      <label>
        <span class="d-block">How did you hear about us?</span>
        <input name="customerReferredBy" type="text" v-model="customerReferredBy" v-validate="'required'">
      </label>
      <hr>
      <div class="fscrForm__btn-container">
        <button type="button" @click="goToPage(4)" class="fscr__button fscr__button--primary">Back</button>
        <button type="button" @click="handleFfifthPageSubmission()" class="fscr__button fscr__button--primary">Next</button>
      </div>
    </fieldset>

    <!-- Page 6 -->
    <fieldset class="fieldset">
      <label>
        <input type="checkbox" v-model="disclaimerAccepted" :value="true">
        <span>I, the enrolled participant(s) and/or parent/guardian of the participant(s) agree and understand that swimming is a hazardous activity. I recognize that there are risks inherent in the sport of swimming, including but not limited to, paralyzing injuries and death. I am aware that participation in swim lessons does not guarantee that participant will be water-safe.</span>
      </label>
      <label>
        <span class="d-block">Order total - Your card will not be charged until you are matched with an instructor.</span>
        <input type="text" readonly> USD
      </label>
    </fieldset>

    <form action="/charge" method="post" id="payment-form">
      <div class="form-row">
        <label for="card-element">
          Credit or debit card
        </label>

        <div id="card-number-element">
          <!-- A Stripe Element will be inserted here. -->
        </div>
        <!-- Used to display Element errors. -->
        <div id="card-number-errors" role="alert"></div>

        <div id="card-expiry-element">
          <!-- A Stripe Element will be inserted here. -->
        </div>
        <!-- Used to display Element errors. -->
        <div id="card-expiry-errors" role="alert"></div>

        <div id="card-cvc-element">
          <!-- A Stripe Element will be inserted here. -->
        </div>
        <!-- Used to display Element errors. -->
        <div id="card-cvc-errors" role="alert"></div>

      </div>

      <button>Submit Payment</button>
    </form>

  </div>

</template>

<script>
  
import Multiselect from 'vue-multiselect'
import Guest from './components/Guest.vue'
import Parent from './components/Parent.vue'
import Student from './components/Student.vue'
import LessonPackage from './components/LessonPackage.vue'

export default {
  
  name: 'app',

  components: {
    Guest,
    Parent,
    Student,
    Multiselect,
    LessonPackage
  },

  data() {
    return {
      step: 1,
      page: 1,
      lessons: [],
      promoCode: null,
      form: {
        serverResponse: {}
      },
      daysThatWork: null,
      numberOfParents: 0,
      numberOfStudents: 0,
      guestIsOnlyParent: true,
      customerReferredBy: null,
      disclaimerAccepted: false,
      selectedLessonDuration: 30,
      displayableLessonPackages: [],
      weekdayTimeRangeAvailability: null,
      allLessonPackages: [
        {
          title: "Learn to Swim Program",
          durations: [
            {
              duration: 30,
              price: 45.00
            },
            {
              duration: 60,
              price: 70.00
            }
          ]
        },
        {
          title: "Water Aerobics",
          durations: [
            {
              duration: 60,
              price: 70.00
            }
          ]
        },
      ],
    }
  },

  mounted() {
    this.lessons = this.getLessonsFromApi();
    this.displayableLessonPackages = this.getDisplayableLessonPackages();
  },

  watch: {
    selectedLessonDuration: function(newDuration, oldDuration) {
      this.displayableLessonPackages = this.getDisplayableLessonPackages();
    }
  },

  methods: {

    goToPage(pageNumber) {
      //
    },

    getStudent(id) {
      var student = null,
          students = this.$refs.students;
      students.forEach(s => {
        if(s.id == id) {
          student = s;
        }
      });
      return student;
    },

    /**
     * Valdiate Guest
     * If guest is valid, create Form Entry and Guest 
     *
     */
    async handleFirstPageSubmission() {

      var guestValidated = await this.$refs.guest.validate();

      // if guest hasn't been created yet, we need to do a post request
      if(!this.$refs.guest.serverResponse.hasOwnProperty('id')) {

        // if guest is valid, create form entry
        if(guestValidated) {

          if (!this.form.serverResponse.hasOwnProperty('id')) {
            this.saveForm();
          }

          // on successful form entry creation, create guest entry
          this.$on('form:create', () => {
            this.$refs.guest.sendToApi(this.form.serverResponse.id);
          });
        }

      }

      // otherwise we need to update the guest
      else {
        this.updateGuest();
      }

    },

    saveForm() {
      var request = {};
      this.$http.post(this.API_BASE_URL + '/forms/create', request)
        .then(response => {
          this.$set(this.form, 'serverResponse', response.data.form);
          this.$emit('form:create');
        })
        .catch(error => {
          this.$set(this.form, 'serverResponse', JSON.stringify(error.data));
          this.$emit('form:error');
        });
    },

    updateGuest() {
      var request = {};
      request.first_name = this.$refs.guest.firstName;
      request.last_name = this.$refs.guest.lastName;
      request.email_address = this.$refs.guest.email;
      request.phone_number = this.$refs.guest.phone;
      request.zip_code = this.$refs.guest.zip;
      request.pool_access = this.$refs.guest.poolAccess;
      this.$http.put(this.API_BASE_URL + '/guests/' + this.apiResponse.guest.success.id, request)
        .then(response => {
          this.$set(this.apiResponse.guest, 'success', response.data.guest);
          this.$emit('guest:update');
        })
        .catch(error => {
          this.$set(this.apiResponse.guest, 'error', error.data);
          this.$emit('guest:update:error');
        });
    },

    /**
     * Validate Parents & Students
     * If valid, create Parents & Students
     *
     */
    async handleSecondPageSubmission() {

      var condition = this.apiResponse.form.success != 'undefined' && this.apiResponse.guest.success != 'undefined' && !this.apiResponse.parents.lenth;

      if(condition) {
        var request           = {},
            parentsValidated  = false,
            studentsValidated = false;
        if(this.guestIsOnlyParent == true) {
          parentsValidated  = true;
          studentsValidated = await this.validateStudents();
        }
        else {
          parentsValidated  = await this.validateParents();
          studentsValidated = await this.validateStudents();
        }
        if(studentsValidated && parentsValidated && this.guestIsOnlyParent) {
          request = {};
          request.name = this.$refs.guest.firstName + " " + this.$refs.guest.lastName;
          request.email = this.$refs.guest.email;
          request.phone_number = this.$refs.guest.phone;
          request.form_entry_id = this.apiResponse.form.success.id;
          request.students = this.$refs.students.map(s => s.id);
          this.saveParents([request]);
        }
        else if (studentsValidated && parentsValidated && !this.guestIsOnlyParent) {
          console.log('validated');
        }
      }
    },

    saveParents(parentRequests) {
      parentRequests.forEach(parentRequest => {
        console.log('making the following request: ', parentRequest);
        this.$http.post(this.API_BASE_URL + '/guardians/create', parentRequest)
          .then(response => {
            this.apiResponse.parents.push(response.data.guardian);
            var parentId = response.data.guardian.id;
            parentRequest.students.forEach(id => {
              var request = {},
                  student = this.getStudent(id);
              if(student) {
                request.student_name = student.name;
                request.student_date_of_birth = student.dob;
                request.form_entry_id = this.apiResponse.form.success.id;
                request.guardian_id = parentId;
                this.saveStudent(request);
              }
            });
          })
          .catch(error => {
            console.log('parent creation error', error.response);
            this.apiResponse.parentsErrors.push({error: JSON.stringify(error.response)});
            this.$emit('parent:create:error');
          });
      })
    },

    saveStudent(studentRequest) {
      this.$http.post(this.API_BASE_URL + '/students/create', studentRequest)
        .then(response => {
          this.apiResponse.students.push(response.data.student);
          this.$emit('student:create');
        })
        .catch(error => {
          this.apiResponse.parentsErrors.push(error.data);
          this.$emit('student:create:error');
        });
    },

    /**
     * Validate Students
     *
     */
    async validateStudents() {
      
      var promises = [],
          studentValidators = [];

      for(var i=0; i < this.numberOfStudents; i++) {
        studentValidators = studentValidators.concat([
          this.$validator.validate('student_' + (i + 1) + '.name'),
          this.$validator.validate('student_' + (i + 1) + '.dob'),
        ]);
      }

      promises = promises.concat([this.$validator.validate('numberOfStudents')]);
      promises = promises.concat(...studentValidators);

      var validate = Promise.all(promises);

      // await results of promise and ensure they are all true
      var validated = await validate.then(result => validated = result.every(isValid => isValid));
      if(validated) {
        return true;
      }
      return false;
    },

    async validateParents() {
      
      var promises = [],
          parentValidators = [];

      for(var i=0; i < this.numberOfParents; i++) {
        parentValidators = parentValidators.concat([
          this.$validator.validate('parent_' + (i) + '.name'),
          this.$validator.validate('parent_' + (i) + '.email'),
          this.$validator.validate('parent_' + (i) + '.phone'),
        ]);
      }

      promises = promises.concat([this.$validator.validate('numberOfParents')]);
      promises = promises.concat(...parentValidators);

      var validate = Promise.all(promises);

      // await results of promise and ensure they are all true
      var validated = await validate.then(result => validated = result.every(isValid => isValid));
      if(validated) {
        return true;
      }
      return false;
    },

    handleThirdPageSubmission() {

    },

    getLessonsFromApi() {
      
    },

    getDisplayableLessonPackages() {
      var res = [],
          lesson = {};
      this.allLessonPackages.forEach(lp => {
        lp.durations.forEach(d => {
          if(d.duration == this.selectedLessonDuration) {
            lesson = {};
            lesson.title    = lp.title;
            lesson.duration = d.duration;
            lesson.price    = d.price;
            res.push(lesson);
          }
        });
      });
      return res;
    },

    checkPromoCode() {

    }

  }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style lang="scss">

  .fscr {

    .d-block {
      display: block;
    }

    .input-wrap {
      margin: 15px 0;
    }

    .fieldset {
      border: none;
      margin: 0;
      padding: 0;
    }

  }
  
</style>
