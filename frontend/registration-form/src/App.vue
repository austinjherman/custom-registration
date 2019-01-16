<template>

  <div id="app" class="fscr">
  <div class="fscr-app-container">
    
    <!-- Page 1 -->
    <div v-show="activePage==1" class="fscr-fieldset-wrap">
      <fieldset class="fscr-fieldset">
        <legend class="fscr-page-title">Your Contact Information</legend>
        <Guest ref="guest"/>
        <hr class="fscr-line-break">
        <div class="fscr-d-flex fscr-justify-content-end">
          <button type="button" @click="handleFirstPageSubmission()" class="fscr-button fscr-button--primary">Next</button>
        </div>
      </fieldset>
    </div>

    <!-- Page 2 -->
    <div v-show="activePage==2">
      <div class="fscr-fieldset-wrap">
        <fieldset class="fscr-fieldset">
          <legend class="fscr-page-title">Student Information</legend>
          <div class="fscr-input-wrap">
            <label class="fscr-input-label">
              <span class="fscr-d-block fscr-input-label-text">How many students are you enrolling? <span class="fscr-asterisk--required">*</span></span>                
              <select name="numberOfStudents" v-validate="'required|min_value:1'" v-model.number="numberOfStudents" class="fscr-input">
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
              <span class="fscr-d-block fscr-input-error">{{ this.validator.first('numberOfStudents') }}</span>
            </label>
          </div>
          <div v-for="n in numberOfStudents" v-bind:key="n">
            <Student :vvScope="'student_' + n" :ref="'students'" />
          </div>
        </fieldset>

        <fieldset class="fscr-fieldset">
          <legend class="fscr-page-title fscr-d-none">Parent Information</legend>
          <div class="fscr-input-wrap">
            <fieldset class="fscr-fieldset">
              <legend class="fscr-input-label fscr-input-label-text">Are you the parent/guardian of all the students? <span class="fscr-asterisk--required">*</span></legend>
              <label class="fscr-input-label">
                <span class="fscr-d-inline-block">Yes</span>
                <input name="guestIsOnlyParent" type="radio" value="true" v-model="guestIsOnlyParent">
              </label>
              <label class="fscr-input-label">
                <span class="fscr-d-inline-block">No</span>
                <input name="guestIsOnlyParent" type="radio" value="false" v-model="guestIsOnlyParent">
              </label>
              <span class="fscr-d-block fscr-input-error">{{ this.validator.first('guestIsOnlyParent') }}</span>
            </fieldset>
          </div>
          <div v-if="guestIsOnlyParent == 'false'">
            <div class="fscr-input-wrap">
              <label class="fscr-input-label">
                <span class="fscr-d-block fscr-input-label-text">How many parents are you signing up? <span class="fscr-asterisk--required">*</span></span>
                <select name="numberOfParents" v-validate="'required|min_value:1'" v-model.number="numberOfParents" class="fscr-input">
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
                <span class="fscr-d-block fscr-input-error">{{ validator.first('numberOfParents') }}</span>
              </label>
            </div>
          </div>
          <div v-if="guestIsOnlyParent == 'false'">
            <div v-for="n in numberOfParents" v-bind:key="n">
              <Parent :vvScope="'parent_' + (n-1)" ref="parents"/>
            </div>
          </div>
          <hr class="fscr-line-break">
          <div class="fscr-d-flex fscr-justify-content-between">
            <button type="button" @click="goToPage(1)" class="fscr__button fscr__button--primary">Back</button>
            <button type="button" @click="handleSecondPageSubmission()" class="fscr__button fscr__button--primary">Next</button>
          </div>
        </fieldset>
      </div>
    </div>

    <!-- Page 3 -->
    <div v-show="activePage==3">
      <fieldset class="fscr-fieldset">
        <legend class="fscr-page-title">Swim Lesson Package Selection</legend>
        <fieldset class="fscr-fieldset">
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
        <fieldset class="fscr-fieldset">
          <div v-for="lp in displayableLessonPackages">

          </div>
        </fieldset>
        <hr>
        <div class="fscrForm__btn-container">
          <button type="button" @click="goToPage(2)" class="fscr__button fscr__button--primary">Back</button>
          <button type="button" @click="handleThirdPageSubmission()" class="fscr__button fscr__button--primary">Next</button>
        </div>
      </fieldset>
    </div>

    <!-- Page 4 -->
    <div v-show="activePage==4">
      <fieldset class="fscr-fieldset">
        <legend class="fscr-page-title">Preferred Schedule</legend>
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
    </div>

    <!-- Page 5 -->
    <div v-show="activePage==5">
      <fieldset class="fscr-fieldset">
        <legend class="fscr-page-title">Order Summary</legend>
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
    </div>

    <!-- Page 6 -->
    <div v-show="activePage==6">
      <fieldset class="fscr-fieldset">
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

  </div>
  </div>

</template>

<script>
  
import Multiselect from 'vue-multiselect'
import Guest from './components/Guest.vue'
import Parent from './components/Parent.vue'
import Student from './components/Student.vue'

export default {
  
  name: 'app',

  components: {
    Guest,
    Parent,
    Student,
    Multiselect
  },

  data() {
    return {
      lessons: [],
      promoCode: null,
      form: {
        serverResponse: {}
      },
      parents: [
        serverResponse: {}
      ],
      activePage: 1,
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
    if(this.getAllUrlParams().page) {
      this.goToPage(this.getAllUrlParams().page);
    }
  },

  watch: {
    selectedLessonDuration: function(newDuration, oldDuration) {
      this.displayableLessonPackages = this.getDisplayableLessonPackages();
    }
  },

  methods: {

    /**
     * Go to a page of the form
     *
     */
    goToPage(pageNumber) {
      this.activePage = pageNumber;
    },

    /**
     * Get a student component instance
     * 
     */
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

      if(guestValidated) {

        // if guest hasn't been created yet, we need to do a post request
        if(!this.$refs.guest.serverResponse.hasOwnProperty('id')) {

            if (!this.form.serverResponse.hasOwnProperty('id')) {
              this.saveForm();
            }

            // on successful form entry creation, create guest entry
            this.$on('form:create', () => {
              this.$refs.guest.store(this.form.serverResponse.id);
            });

          }

          // otherwise we need to update the guest
          else {
            if(this.$refs.guest.isDirty() && guestValidated) {
              this.$refs.guest.update(this.form.serverResponse.id);
            }
          }

          this.activePage += 1;

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

    /**
     * Validate Parents & Students
     * If valid, create Parents & Students
     *
     */
    async handleSecondPageSubmission() {

          // need to have a form ID provided by server
      var savedForm    = this.form.serverResponse.hasOwnProperty('id'),
          // need to have a guest ID provided by server
          savedGuest   = this.$refs.guest.serverResponse.hasOwnProperty('id'),
          // could have already saved parents
          savedParents = this.$refs.hasOwnProperty('parents') ? this.$refs.parents.every(p => { p.serverResponse.hasOwnProperty('id'); }) : null,
          // need to be enrolling students
          enrollingStudents = await this.$validator.validate('numberOfStudents'),
          // -- //
          parentsValidated  = false,
          studentsValidated = false,
          parent  = {},
          request = {};

      if(savedForm && savedGuest && !savedParents && enrollingStudents) {

        // if guest is only parent
        if(this.guestIsOnlyParent == true) {

          parentsValidated  = true;
          studentsValidated = await this.validateStudents();

          if(studentsValidated) {

            parent.name = this.$refs.guest.firstName + " " + this.$refs.guest.lastName;
            parent.email = this.$refs.guest.email;
            parent.phone_number = this.$refs.guest.phone;
            parent.form_entry_id = this.form.serverResponse.id;

            // create one parent
            this.$http.post(this.API_BASE_URL + '/guardians/create', parent)
              .then(response => {
                // update formEntry id so we know we have one
                var serverResponse = response.data.guardian;
                serverResponse.frontEndId = this.$refs.guest.id;
                this.parents.push(serverResponse);
                this.$emit('parent:create');
              })
              .catch(error => {
                this.parents.push(error.data);
                this.$emit('parent:create:error');
              });

            // create students with the one parent's ID
            this.$on('parent:create', () => {
              if(this.parent.serverResponse.hasOwnProperty('id')) {
                this.$refs.students.forEach(s => {
                  s.store({
                    guardian_id: this.parent.serverResponse.id,
                    form_entry_id: this.form.serverResponse.id
                  });
                });
              }
            });

          }

        }

        // if guest is not only parent
        else {
          parentsValidated  = await this.validateParents();
          studentsValidated = await this.validateStudents();
        }

        /*
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
        */
      }
    },

    /**
     * Validate Students
     *
     */
    async validateStudents() {
      var promise = null,
          results = [],
          validate  = null,
          validated = false;
      this.$refs.students.forEach( async (s) => {
        results.push(...s.validate());
      });
      validate = Promise.all(results);
      validated = await validate.then(result => validated = result.every(isValid => isValid));
      return validated;
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

    },

    getAllUrlParams(url) {

      // get query string from url (optional) or window
      var queryString = url ? url.split('?')[1] : window.location.search.slice(1);

      // we'll store the parameters here
      var obj = {};

      // if query string exists
      if (queryString) {

        // stuff after # is not part of query string, so get rid of it
        queryString = queryString.split('#')[0];

        // split our query string into its component parts
        var arr = queryString.split('&');

        for (var i = 0; i < arr.length; i++) {
          // separate the keys and the values
          var a = arr[i].split('=');

          // set parameter name and value (use 'true' if empty)
          var paramName = a[0];
          var paramValue = typeof (a[1]) === 'undefined' ? true : a[1];

          // (optional) keep case consistent
          paramName = paramName.toLowerCase();
          if (typeof paramValue === 'string') paramValue = paramValue.toLowerCase();

          // if the paramName ends with square brackets, e.g. colors[] or colors[2]
          if (paramName.match(/\[(\d+)?\]$/)) {

            // create key if it doesn't exist
            var key = paramName.replace(/\[(\d+)?\]/, '');
            if (!obj[key]) obj[key] = [];

            // if it's an indexed array e.g. colors[2]
            if (paramName.match(/\[\d+\]$/)) {
              // get the index value and add the entry at the appropriate position
              var index = /\[(\d+)\]/.exec(paramName)[1];
              obj[key][index] = paramValue;
            } else {
              // otherwise add the value to the end of the array
              obj[key].push(paramValue);
            }
          } else {
            // we're dealing with a string
            if (!obj[paramName]) {
              // if it doesn't exist, create property
              obj[paramName] = paramValue;
            } else if (obj[paramName] && typeof obj[paramName] === 'string'){
              // if property does exist and it's a string, convert it to an array
              obj[paramName] = [obj[paramName]];
              obj[paramName].push(paramValue);
            } else {
              // otherwise add the property
              obj[paramName].push(paramValue);
            }
          }
        }
      }

      return obj;
    },

  }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style lang="scss">

  .fscr {

    font-family: 'Open Sans', sans-serif;

    .fscr-d-none {
      display: none;
    }

    .fscr-d-block {
      display: block;
    }

    .fscr-d-inline-block {
      display: inline-block;
    }

    .fscr-d-flex {
      display: flex;
    }

    .fscr-d-flex.wrap {
      flex-wrap: wrap;
    }

    .fscr-justify-content-end {
      justify-content: flex-end;
    }

    .fscr-justify-content-between {
      justify-content: space-between;
    }

    .fscr-app-container {
      max-width: 750px;
      margin: 0 auto;
    }

    .fscr-line-break {
      margin-bottom: 25px;
    }

    .fscr-fieldset-wrap {
      margin: 25px 0;
    }

    .fscr-page-title {
      border-top: 1px solid #000;
      border-bottom: 1px solid #000;
      width: 100%;
      padding: 15px 0;
      font-weight: bold;
      margin-bottom: 25px;
    }

    .fscr-input-error {
      height: 16px;
      color: tomato;
      font-size: 0.65rem;
      padding: 5px 0 0 0;
      font-weight: bold;
    }

    .fscr-asterisk--required {
      color: tomato;
      font-size: 0.75rem;
      vertical-align: top;
    }

    .fscr-input-wrap {
      margin: 5px 0;
    }

    .fscr-input-wrap--half {
      flex: 1 1 50%;
    }

    .fscr-input {
      font-size: 0.9rem;
      padding: 5px;
    }

    .fscr-input-label {
      font-weight: bold;
      padding: 0;
    }

    .fscr-input-label-text {
      margin-bottom: 5px;
    }

    .fscr-fieldset {
      border: none;
      margin: 0;
      padding: 0;
    }

    .fscr-parent-component {
      margin: 0 0 25px 0;
      border: 1px dashed #222;
      padding: 15px;
    }

    .fscr-student-component {
      margin: 0 0 25px 0;
      border: 1px dashed #222;
      padding: 15px;
    }

  }
  
</style>
