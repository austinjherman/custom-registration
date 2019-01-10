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
        <button type="button" @click="goToPage(2)" class="fscr__button fscr__button--primary">Back</button>
        <button type="button" @click="handleFourthPageSubmission()" class="fscr__button fscr__button--primary">Next</button>
      </div>
    </fieldset>


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
      apiResponse: {},
      daysThatWork: null,
      numberOfParents: 0,
      numberOfStudents: 0,
      guestIsOnlyParent: true,
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
    this.displayableLessonPackages = this.getDisplayableLessonPackages();
  },

  watch: {
    selectedLessonDuration: function(newDuration, oldDuration) {
      this.displayableLessonPackages = this.getDisplayableLessonPackages();
    }
  },

  methods: {

    getParents() {

    },

    goToPage(pageNumber) {
      //
    },

    /**
     * Valdiate Guest
     * If valid, create Form Entry and Guest 
     *
     */
    async handleFirstPageSubmission() {
      var request = {},
          validated = await this.$refs.guest.validate();
      if(validated) {
        this.$http.post(this.API_BASE_URL + '/forms/create', request)
          .then(response => {
            this.$set(this.apiResponse, 'form', response.data.form);
            this.$emit('form:create');
          })
          .catch(error => {
            this.$set(this.apiResponse, 'form', JSON.stringify(error));
            this.$emit('form:error');
          });
        this.$on('form:create', () => {
          request = {};
          request.first_name = this.$refs.guest.firstName;
          request.last_name = this.$refs.guest.lastName;
          request.email_address = this.$refs.guest.email;
          request.phone_number = this.$refs.guest.phone;
          request.zip_code = this.$refs.guest.zip;
          request.pool_access = this.$refs.guest.poolAccess;
          request.form_entry_id = this.apiResponse.form.id;
          this.$http.post(this.API_BASE_URL + '/guests/create', request)
          .then(response => {
            // update formEntry id so we know we have one
            this.$set(this.apiResponse, 'guest', response.data.guest);
            this.$emit('guest:create');
          })
          .catch(error => {
            this.$set(this.apiResponse, 'guest', error.data);
            console.log('guest error', error);
            this.$emit('guest:error');
          });
        });
        return true;
      }
      return false;
    },

    /**
     * Validate Parents & Students
     * If valid, create Parents & Students
     *
     */
    async handleSecondPageSubmission() {
      var parentsValidated = false,
          studentsValidated = false;
      if(this.guestIsOnlyParent == true) {
        parentsValidated  = true;
        studentsValidated = await this.validateStudents();
      }
      else {
        parentsValidated  = await this.validateParents();
        studentsValidated = await this.validateStudents();
      }
      if(studentsValidated && parentsValidated) {
        console.log('validated');
      }
      //this.$refs.parents.sendToApi();
      //console.log('students: ', this.$store.state.students.students);
      //console.log('parents: ', this.$store.state.parents.parents);
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
