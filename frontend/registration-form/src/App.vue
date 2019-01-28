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
            <Student :vvScope="'student_' + n" :ref="'students'" :getParent="getParent" /> 
          </div>
        </fieldset>

        <fieldset class="fscr-fieldset">
          <legend class="fscr-page-title fscr-d-none">Parent Information</legend>
          <div class="fscr-input-wrap">
            <fieldset class="fscr-fieldset">
              <legend class="fscr-input-label fscr-input-label-text">Are you the parent/guardian of all the students? <span class="fscr-asterisk--required">*</span></legend>
              <label class="fscr-input-label">
                <span class="fscr-d-inline-block">Yes</span>
                <input name="guestIsOnlyParent" type="radio" :value="true" v-model="globalState.guestIsOnlyParent">
              </label>
              <label class="fscr-input-label">
                <span class="fscr-d-inline-block">No</span>
                <input name="guestIsOnlyParent" type="radio" :value="false" v-model="globalState.guestIsOnlyParent">
              </label>
              <span class="fscr-d-block fscr-input-error">{{ this.validator.first('guestIsOnlyParent') }}</span>
            </fieldset>
          </div>
          <div v-if="globalState.guestIsOnlyParent == false">
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
          <div v-if="globalState.guestIsOnlyParent == false">
            <div v-for="n in numberOfParents" v-bind:key="n">
              <Parent :vvScope="'parent_' + (n-1)" ref="parents" :getStudent="getStudent"/>
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
        <fieldset class="fscr-fieldset fscr-fieldset--durations">
          <legend>How long would you like each lesson to last for your student<span v-if="numberOfStudents > 1">s</span>?</legend>
          <span class="fscr-d-block fscr-input-error">{{ durationRadioError }}</span>
          <div class="fscr-radio--durations">
            <div class="fscr-radio--duration">
              <label class="fscr-d-block">
                <span class="fscr-d-block fscr-radio--duration__content">
                  <span class="fscr-d-block fscr-radio--duration__duration">30</span>
                  <span class="fscr-d-block fscr-radio--duration__duration-text">Minute Lessons</span>
                </span>
                <input name="lessonDuration" type="radio" @click="handleLessonDurationSelect" v-model="globalState.selectedLessonDuration" value="30" v-validate="'required'" class="fscr-d-none">
                <span v-if="Number(globalState.selectedLessonDuration) !== 30" class="fscr-d-block fscr-radio--duration__select-text">Select</span>
                <span v-if="Number(globalState.selectedLessonDuration) === 30" class="fscr-d-block fscr-radio--duration__select-text fscr-radio--duration__select-text--selected">OK!</span>
              </label>
            </div>
            <div class="fscr-radio--duration">
              <label class="fscr-d-block">
                <span class="fscr-d-block fscr-radio--duration__content">
                  <span class="d-block fscr-radio--duration__duration">60</span>
                  <span class="fscr-d-block fscr-radio--duration__duration-text">Minute Lessons</span>
                </span>
                <input name="lessonDuration" type="radio" @click="handleLessonDurationSelect" v-model="globalState.selectedLessonDuration" value="60" v-validate="'required'" class="fscr-d-none">
                <span v-if="Number(globalState.selectedLessonDuration) !== 60" class="fscr-d-block fscr-radio--duration__select-text">Select</span>
                <span v-if="Number(globalState.selectedLessonDuration) === 60" class="fscr-d-block fscr-radio--duration__select-text fscr-radio--duration__select-text--selected">OK!</span>
              </label>
            </div>
          </div>
        </fieldset>
        <fieldset class="fscr-fieldset fscr-fieldset--lessons">
          <legend class="fscr-d-none">Lesson Packages</legend>
          <span class="fscr-d-block fscr-input-error">{{ lessonRadioError }}</span>
          <ul class="fscr-d-flex fscr-lesson-package-list">
            <li class="fscr-lesson-package-item">
              <label class="fscr-d-block">
                <div v-for="lesson in lessons">
                  <div v-for="durationObj in lesson.durations">
                    <div v-if="Number(durationObj.duration) === Number(globalState.selectedLessonDuration) && lesson.name == 'Swimming Lesson'"> 
                      <div class="fscr-lesson-package-item-top">
                        <span class="fscr-d-block">Learn to Swim Program</span>
                        <!-- Using the duration ID here will allow us to grab the selected lesson in the backend -->
                        <input type="radio" :value="durationObj.id" v-model="globalState.selectedLessonDurationServerId" class="fscr-d-none" @click="handleLessonSelect(lesson, 8)">
                        <span class="fscr-d-block">{{ lesson.name }}</span>
                        <span class="fscr-d-block">Lesson Quantity: 8</span>
                        <span class="fscr-d-block">${{ durationObj.price }} / {{ durationObj.duration }} mins</span>
                      </div>
                      <div class="fscr-lesson-package-item-bottom">
                        <div v-if="globalState.selectedLessonDurationServerId != durationObj.id">Select</div>
                        <div v-if="globalState.selectedLessonDurationServerId == durationObj.id">OK!</div>
                      </div>
                    </div>
                  </div>
                </div>
              </label>
            </li>

            <li class="fscr-lesson-package-item">
              <label class="fscr-d-block">
                <div v-for="lesson in lessons">
                  <div v-for="durationObj in lesson.durations">
                    <div v-if="Number(durationObj.duration) === Number(globalState.selectedLessonDuration) && lesson.name == 'Water Aerobics'"> 
                      <div class="fscr-lesson-package-item-top">
                        <span class="fscr-d-block">Water Aerobics Lessons</span>
                        <!-- Using the duration ID here will allow us to grab the selected lesson in the backend -->
                        <input type="radio" :value="durationObj.id" v-model="globalState.selectedLessonDurationServerId" class="fscr-d-none" @click="handleLessonSelect(lesson, globalState.waterAerobicsLessonQty)">
                        <span class="fscr-d-block">{{ lesson.name }}</span>
                        <label>
                          <span>Lesson Quantity: </span>
                          <select v-model="globalState.waterAerobicsLessonQty" @change="handleWaterAerobicsQtyChange(durationObj, $event)">
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
                        <span class="fscr-d-block">${{ durationObj.price }} / {{ durationObj.duration }} mins</span>
                      </div>
                      <div class="fscr-lesson-package-item-bottom">
                        <div v-if="globalState.selectedLessonDurationServerId != durationObj.id">Select</div>
                        <div v-if="globalState.selectedLessonDurationServerId == durationObj.id">OK!</div>
                      </div>
                    </div>
                  </div>
                </div>
              </label>
            </li>

          </ul>

        </fieldset>
        <hr>
        <div class="fscr-d-flex fscr-justify-content-between">
          <button type="button" @click="goToPage(2)" class="fscr__button fscr__button--primary">Back</button>
          <button type="button" @click="handleThirdPageSubmission()" class="fscr__button fscr__button--primary">Next</button>
        </div>
      </fieldset>
    </div>

    <!-- Page 4 -->
    <div v-show="activePage==4">
      <fieldset class="fscr-fieldset">
        <legend class="fscr-page-title">Preferred Schedule</legend>
        <div class="fscr-multiselect-wrapper">
          <label>
            <span class="d-block">What days work for you?</span>
            <multiselect name="daysThatWork" v-model="globalState.daysThatWork" :options="['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']" :multiple="true" v-validate="'required'" :vv-validate-on="'input|close'"></multiselect>
            <span class="fscr-d-block fscr-input-error">{{ validator.first('daysThatWork') }}</span>
          </label>
        </div>
        <div class="fscr-multiselect-wrapper">
          <label>
            <span class="d-block">Select your time range availability for weekdays.</span>
            <multiselect name="weekdayTimeRangeAvailability" v-model="globalState.weekdayTimeRangeAvailability" :options="['Morning (8am - 12pm)', 'Afternoon (12pm - 4pm)', 'Evening (4pm - 8pm)']" :multiple="true" v-validate="'required'" :vv-validate-on="'input|close'"></multiselect>
            <span class="fscr-d-block fscr-input-error">{{ validator.first('weekdayTimeRangeAvailability') }}</span>
          </label>
        </div>
        <div class="fscr-schedule-additional-info-wrapper">
          <label>
            <span class="fscr-d-block">Additional scheduling information, if any.</span>
            <textarea rows="12" v-model="globalState.scheduleDescription"></textarea>
          </label>
        </div>
        <hr>
        <div class="fscr-d-flex fscr-justify-content-between">
          <button type="button" @click="goToPage(3)" class="fscr__button fscr__button--primary">Back</button>
          <button type="button" @click="handleFourthPageSubmission()" class="fscr__button fscr__button--primary">Next</button>
        </div>
      </fieldset>
    </div>

    <!-- Page 5 -->
    <div v-show="activePage==5">

      <fieldset class="fscr-fieldset">
        <legend class="fscr-page-title">Order Summary</legend>

        <div class="fscr-promo-section">
          <label>
            <span class="d-block">Apply a promo code.</span>
            <input type="text" v-model="promoCode">
          </label>
          <button type="button" @click="checkPromoCode">Apply</button>
        </div>

        <hr>
        
        
        <section>
          <h1>Lesson Package Cost Breakdown</h1>
        </section>

        <hr>
  
        <label>
          <span class="fscr-d-block">How did you hear about us?</span>
          <input name="customerReferredBy" type="text" v-model="customerReferredBy" v-validate="'required'">
        </label>

        <hr>

        <div class="fscr-d-flex fscr-justify-content-between">
          <button type="button" @click="goToPage(4)" class="fscr__button fscr__button--primary">Back</button>
          <button type="button" @click="handleFifthPageSubmission()" class="fscr__button fscr__button--primary">Next</button>
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

        <hr>

        <div class="fscr-d-flex fscr-justify-content-between">
          <button type="button" @click="goToPage(5)" class="fscr__button fscr__button--primary">Back</button>
          <button type="button" @click="handleSixthPageSubmission()" class="fscr__button fscr__button--primary">Next</button>
        </div>

      </form>
    </div>

  </div>
  </div>

</template>

<script>
  
import GlobalState from './GlobalState'
import Multiselect from 'vue-multiselect'
import Guest       from './components/Guest.vue'
import Parent      from './components/Parent.vue'
import Student     from './components/Student.vue'

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
      globalState: GlobalState,
      promoCode: null,
      activePage: 1,
      numberOfParents: 0,
      numberOfStudents: 0,
      customerReferredBy: null,
      disclaimerAccepted: false,
      durationRadioError: null,
      lessonRadioError: null
    }
  },

  mounted() {
    this.getLessonsFromApi();
    if(this.getAllUrlParams().page) {
      this.goToPage(this.getAllUrlParams().page);
    }
  },

  watch: {
  },

  methods: {

    // work these out
    checkPromoCode() {},

    /**
     | ----------------------------------------------------------
     | Button Handlers
     | ----------------------------------------------------------
     |
     | The following functions deal with the buttons that submit
     | each page of the form.
     |
     */

    /**
     * Handle the page 1 submission button.
     * 
     * If Form Entry / Guest haven't yet been saved, save them to DB.
     * If Form Entry / Guest have been saved already, update them if inputs have changed.
     *
     * @params none
     * @return void
     */
    async handleFirstPageSubmission() {

      var promises = [],
          guestValidated = await this.$refs.guest.validate(),
          formCreated  = false,
          guestCreated = false,
          guestUpdated = false,
          guestUpdateAsParent = false;

      if(guestValidated) {

        // if guest hasn't been saved in DB yet, we need to do that
        if(!this.$refs.guest.serverResponse.guest.success.hasOwnProperty('id')) {

          // if form hasn't been created yet, then we need to do that first
          if (!this.globalState.serverResponse.form.success.hasOwnProperty('id')) {
            formCreated = await this.saveForm();
          }

          // when the form is created, create the guest
          if(formCreated) {
            guestCreated = await this.$refs.guest.store();
          }

          // when the guest is created, go to next page
          if(guestCreated) {
            this.activePage += 1;
          }

        }

        // otherwise we need to update the guest
        else {

          // if the guest inputs do not match the server and they are valid
          if(this.$refs.guest.isDirty() && guestValidated) {

            // update the guest
            promises.push(this.$refs.guest.update());
            
            // if guest is parent then we need to update the parent
            if(this.$refs.guest.serverResponse.parent.hasOwnProperty('id')) {
              promises.push(this.$refs.guest.updateAsParent());
            }

          }

          // when guest is updated (and, if applicable, the parent is updated)
          // go to next page
          Promise.all(promises).then(result => {
            this.activePage += 1;
          });

        }

      }

    },

    /**
     * Handle page 2 submission button.
     * 
     * If Guest is only parent, make a new parent with guest information. Additionally, delete
     * any parents that have been saved to DB.
     *
     * If Guest is not only parent, make new parents with parent information. Additionally, 
     * if a parent was created with guest information, delete it.
     *
     * Once parents have been sorted out, save students.
     *
     * @params none
     * @return void
     */
    async handleSecondPageSubmission() {

      var savedForm    = this.globalState.serverResponse.form.success.hasOwnProperty('id'),
          savedGuest   = this.$refs.guest.serverResponse.guest.success.hasOwnProperty('id'),
          enrollingStudents = await this.$validator.validate('numberOfStudents'),
          parentsValidated  = false,
          studentsValidated = false,
          studentPromises   = [],
          promises = [];

      // check to make sure we have a form, guest, and students to enroll
      if(savedForm && savedGuest && enrollingStudents) {

        // check to make sure student fields are valid
        studentsValidated = await this.validateStudents();
        if(studentsValidated) {

          // check to see if the guest is the only parent
          if(this.globalState.guestIsOnlyParent == true) {

            // if the guest hasn't already been saved, create a new parent with guest info and save to DB
            // if the guest has already been saved, update is taken care of on previous page submit
            if(!this.$refs.guest.serverResponse.parent.success.hasOwnProperty('id')) {
              promises.push(this.$refs.guest.saveAsParent());
            }
            
          }

          // if the guest is not the only parent
          else {

            // check to see if parent fields are valid
            parentsValidated  = await this.validateParents();
            if(parentsValidated) {

              // delete the parent that was created with the guest information if it exists. 
              // students are orphaned in backend when a parent is deleted
              // we will be resassigning their parents
              if(this.$refs.guest.serverResponse.parent.hasOwnProperty('id')) {
                promises.push(this.$refs.guest.deleteAsParent());
              }

              // save/update parents in DB
              if(typeof this.$refs.parents != 'undefined' && this.$refs.parents.length > 0) {
                this.$refs.parents.forEach(p => {
                  // if hasn't been created yet, store
                  if(!p.serverResponse.success.hasOwnProperty('id') && p.isDirty()) {
                    promises.push(p.store());
                  }
                  // if has been created, update
                  else if(p.serverResponse.success.hasOwnProperty('id') && p.isDirty()) {
                    promises.push(p.update());
                  }
                });
              }

            }

          }

          // create/update students after all parent creation requests are resolved
          // the parent/student relations are handled via the handleParentStudentRelationship
          // function on the parent component. These relationships are resolved when a 
          // student checkbox is checked.
          Promise.all(promises).then(response => {
            this.$refs.students.forEach(s => {
              if(!s.serverResponse.success.hasOwnProperty('id')) {
                studentPromises.push(s.store());
              }
              else if(s.serverResponse.success.hasOwnProperty('id')) {
                studentPromises.push(s.update());
              }
            });
            Promise.all(studentPromises).then(result => {
              this.goToPage(3);
            });
          });

        }

      }
    },

    /**
     * Handle page 3 submission button.
     * 
     * This page updates the students' lessons, lesson durations, and lesson quantities.
     *
     * @params none
     * @return void
     */
    handleThirdPageSubmission() {

      var errors = false,
          promises = [];

      // make sure we have a valid lesson duration selected
      if(Number(this.globalState.selectedLessonDuration) != 60 && Number(this.globalState.selectedLessonDuration) != 30) {
        this.durationRadioError = "Please choose a valid lesson duration.";
        errors = true;
      }

      // make sure we have a valid lesson plan selected
      if(this.globalState.selectedLessonDurationServerId === null) {
        this.lessonRadioError = "Please choose a lesson plan.";
        errors = true;
      }

      if(errors) {
        return;
      }

      // reset errors if validation passes
      this.lessonRadioError = null;
      this.durationRadioError = null;

      // update students;
      this.$refs.students.forEach(s => {
        s.lessonDurationServerId = this.globalState.selectedLessonDurationServerId;
        s.lessonQty = this.globalState.selectedLessonQty;
        if(s.isDirty(3)) {
          promises.push(s.update());
        }
      });

      Promise.all(promises).then(result => {
        this.goToPage(4);
      });

    },

    /**
     * Handle page 4 submission button.
     * 
     * This page creates or updates the student's schedule.
     *
     * @params none
     * @return void
     */
    async handleFourthPageSubmission() {

      // validate fields
      var promises    = [],
          fieldsValid = false;
      promises.push(this.$validator.validate('daysThatWork'));
      promises.push(this.$validator.validate('weekdayTimeRangeAvailability'));
      fieldsValid = await Promise.all(promises).then(result => fieldsValid = result.every(v => v == true));
      if(!fieldsValid) return

      // create schedules
      var request   = {},
          responses = [];

      this.$refs.students.forEach(s => {

        request = {};
        request.days_available = this.globalState.daysThatWork;
        request.time_availability_weekdays = this.globalState.weekdayTimeRangeAvailability;
        request.student_id = Number(s.serverResponse.success.id);
        request.description = this.globalState.scheduleDescription;
        s.$set(s.schedule, 'local', request);

        // if we need to save
        if(!s.schedule.server.success.hasOwnProperty('id')) {
          responses.push(this.saveSchedule(s));
        }

        // else we need to update
        else {

          // first determine if the local schedule is synced with DB
          var isDirty = 
            !s.schedule.server.success.hasOwnProperty('id') || (
            s.schedule.local.days_available != s.schedule.server.success.days_available ||
            s.schedule.local.time_availability_weekdays != s.schedule.server.success.time_availability_weekdays)

          // proceed if we need to update a schedule
          if(isDirty) {
            responses.push(this.updateSchedule(s));
          }

        }

      });

      Promise.all(responses).then(result => {
        this.goToPage(5);
      });

    },

    /**
     * Handle page 5 submission button.
     * 
     *
     * @params none
     * @return void
     */
    handleFifthPageSubmission() {
      this.goToPage(6);
    },

    /**
     | ----------------------------------------------------------
     | Getters
     | ----------------------------------------------------------
     |
     | The following functions get data from the API.
     |
     */
    getLessonsFromApi() {
      this.$http.get(this.API_BASE_URL + '/lessons/')
        .then(response => {
          this.lessons = response.data.lessons;
        })
        .catch(error => {
          this.lessons = JSON.stringify(error.data);
        });
    },


    /**
     | ----------------------------------------------------------
     | Validation Helpers
     | ----------------------------------------------------------
     |
     | The following functions deal with field validation.
     |
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

    async validateParents() {
      var promise = null,
          results = [],
          validate  = null,
          validated = false;
      this.$refs.parents.forEach( async (p) => {
        results.push(...p.validate());
      });
      validate = Promise.all(results);
      validated = await validate.then(result => validated = result.every(isValid => isValid));
      return validated;
    },

    /**
     | ----------------------------------------------------------
     | Database Helpers
     | ----------------------------------------------------------
     |
     | The following functions help with CRUD operations.
     |
     */

    saveForm() {

      var request = {};

      return new Promise((resolve, reject) => {
        this.$http.post(this.API_BASE_URL + '/forms/create', request)
          .then(response => {
            this.globalState.serverResponse.form.success = response.data.form;
            resolve(response.data.form);
          })
          .catch(error => {
            this.globalState.serverResponse.form.error = JSON.stringify(error);
            reject(error);
          });
      });

    },

    saveSchedule(student) {
      return new Promise((resolve, reject) => {
        this.$http.post(this.API_BASE_URL + '/schedules/create', student.schedule.local)
          .then(response => {
            student.$set(student.schedule.server, 'success', response.data.schedule);
            resolve(response.data.schedule);
          })
          .catch(error => {
            student.$set(student.schedule.server, 'error', error.data);
            reject(error);
          });
      });
    },

    updateSchedule(student) {
      return new Promise((resolve, reject) => {
        this.$http.put(this.API_BASE_URL + '/schedules/update/' + student.schedule.server.success.id, student.schedule.local)
          .then(response => {
            student.$set(student.schedule.server, 'success', response.data.schedule);
            resolve(response.data.schedule);
          })
          .catch(error => {
            student.$set(student.schedule.server, 'error', error.data);
            reject(error);
          });
      });
    },

    /**
     | ----------------------------------------------------------
     | Helper Functions
     | ----------------------------------------------------------
     |
     | The following functions perform some helpful actions
     |
     */

    /**
     * Go to a page of the form
     *
     * @param int pageNumber
     * @return void
     */
    goToPage(pageNumber) {
      this.activePage = pageNumber;
    },

    /**
     * Get a student component instance
     * 
     * @param int id
     * @return Component student
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
     * Get a parent component instance
     * 
     * @param int id
     * @return Component parent
     */
    getParent(id) {
      if(this.globalState.guestIsOnlyParent) {
        return this.$refs.guest;
      }
      var parent = null,
          parents = this.$refs.parents;
      if(typeof parents != 'undefined' && parents.length > 0) {
        parents.forEach(p => {
          if(p.id == id) {
            parent = p;
          }
        });
      }
      return parent;
    },

    handleLessonDurationSelect() {
      if(this.durationRadioError !== null) {
        this.durationRadioError = null;
      }
      this.globalState.selectedLessonDurationServerId = null;
    },

    handleLessonSelect(lesson, lessonQty) {
      if(this.lessonRadioError !== null) {
        this.lessonRadioError = null;
      }
      this.globalState.selectedLessonQty = lessonQty;
    },

    handleWaterAerobicsQtyChange(duration, $e) {
      if(duration.id == this.globalState.selectedLessonDurationServerId && Number($e.target.value) !== Number(this.globalState.selectedLessonQty)) {
        this.globalState.selectedLessonQty = $e.target.value;
      }
    },

    /**
     * Parse the query string and get all URL params.
     * 
     * @param string url
     * @return Object the URL params
     */
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

    .fscr-fieldset--durations {
      margin: 25px 0;
    }

    .fscr-fieldset--durations legend {
      margin-bottom: 15px;
    }

    .fscr-radio--durations {
      display: flex;
      justify-content: space-around;
      margin: 0;
    }

    .fscr-radio--duration {
      border: 1px solid #333;
      flex: 0 1 30%;
      text-align: center;
    }

    .fscr-radio--duration label:hover {
      cursor: pointer;
    }

    .fscr-radio--duration__content {
      padding: 15px;
    }

    .fscr-radio--duration__duration {
      font-size: 2rem;
      font-weight: bold;
    }

    .fscr-radio--duration__select-text {
      border-top: 1px solid #333;
      padding: 5px;
    }

    .fscr-fieldset--lessons {
      margin: 25px 0;
    }

    .fscr-fieldset--lessons legend {
      margin-bottom: 15px;
    }

    .fscr-lesson-package-list {
      list-style: none;
      margin: 0;
      padding: 0;
      justify-content: space-around;
    }

    .fscr-lesson-package-item {
      border: 1px solid #333;
      flex: 0 1 30%;
      text-align: center;
    }

    .fscr-multiselect-wrapper {
      margin: 15px 0;
      width: 50%;
    }

    .fscr-schedule-additional-info-wrapper {
      margin: 15px 0;
    }

    .fscr-schedule-additional-info-wrapper textarea {
      margin-top: 10px;
      margin-bottom: 25px;
      width: 50%;
    }

    .multiselect {
      margin-top: 10px;
    }

    .multiselect__option--highlight {
      background: #333;
    }

    .multiselect__tag {
      background: #333;
    }

    .multiselect__tag-icon:focus, 
    .multiselect__tag-icon:hover {
      background-color: #aaa;
    }

    .multiselect__tag-icon:focus:after, 
    .multiselect__tag-icon:hover:after {
      color: #333;
    }

    .multiselect__tag-icon:after {
      color: #fff;
    }

    .multiselect__option--selected.multiselect__option--highlight:after {
      background: #aaa;
    }

    .multiselect__option--highlight:after {
      background: #aaa;
      color: #333;
    }

  }
  
</style>
