/**
 *
 * A note about this javascript:
 *
 * There are some complex objects in here that are 
 * dynamically created. Notably, the "students" and
 * "parents" objects and those that are nested within
 * these objects. Vue.js prefers to have all properties 
 * within objects predefined, or it throws an error during 
 * render. Unfortunately, this was not possible here because
 * we don't know how many students or parents are going to be
 * signing up.
 *
 * What this means is that anytime you're changing an object,
 * you need to make sure that it has the same properties throughout
 * this file, or you will probably have an error during render.
 *
 */


// using VeeValidate as a plugin
Vue.use(VeeValidate, {
  errorBagName: 'validator',
  events: 'input'
});

var fscrForm = new Vue({
  
  el: '#fscrForm',

  components: {
    'datepicker': vuejsDatepicker
  },

  data: {

    // api base URL
    api: "http://localhost/floridaswim/wp-json/fscr/v1", 

    activeFormPage: 1,

    pages: {
      1: {
        submitted: false
      },
      2: {
        submitted: false
      },
      3: {
        submitted: false
      },
    },

    // display the confirm parent/guardian section?
    guestIsParentGuardianForAll: true,

    // the created form entries
    formEntry: {
      created: null
    },

    // guest object
    guest: {
      firstName: null,
      lastName: null,
      email: null,
      phone: null,
      zipCode: null,
      poolAccess: null,
      errors: {
        first_name: null,
        last_name: null,
        email_address: null,
        phone_number: null,
        zip_code: null,
        pool_access: null
      },
      created: null
    },

    // students object
    students: {
      count: 0,
      errors: {
        number_students_enrolling: null
      },
      students: [
        /**
         * if count > 0 at the created event, 
         * then you need to set a default student
         * 
        {
          id: 0,
          name: "",
          dob: "",
          dobOriginal: "",
          save: false,
          created: null
        }
        */
      ]
    },

    // parents guardians
    parents: {
      count: 0,
      errors: {
        number_parents_adding: null
      },
      parents: [
        /**
         * if count > 0 at the created event, 
         * then you need to set a default parent
         * 
        {
          id: 0,
          first_name: "",
          last_name: "",
          email: "",
          phone: "",
          students: [],
          save: false,
          created: null
        }
        */
      ]
    },

  },

  mounted: function () {

    // go to a page based on query param
    this.$nextTick(function () {
      if(this.getQueryParam('fscr-page')) {
        this.goToPage(this.getQueryParam('fscr-page'));
        this.$set(this, 'activeFormPage', this.getQueryParam('fscr-page'));
      }
    });

  },

  methods: {

    /**
     | ---------------------------------------------------------------
     | Helper Methods
     | ---------------------------------------------------------------
     |
     | Here we have some helper methods to help us.
     |
     */

    /** 
     * This exists to set the value of this.guest.poolAccess
     *
     */
    updatePoolAccess: function(boolean) {
      if (boolean === true) {
        this.$set(this.guest, 'poolAccess', 'true');
      }
      else {
        this.$set(this.guest, 'poolAccess', 'false');
      }
    },

    /** 
     * Get a URL query parameter
     *
     */
    getQueryParam: function(parameterName) {
      var result = null,
          tmp = [];
      var items = location.search.substr(1).split("&");
      for (var index = 0; index < items.length; index++) {
        tmp = items[index].split("=");
        if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
      }
      return result;
    },

    /** 
     * This function will allow us to go to pages of the form.
     *
     */
    goToPage: function(pageNumber) {
      var currentPage = this.$el.querySelector('[data-fscr-page="' + this.activeFormPage + '"]');
      var targetPage  = this.$el.querySelector('[data-fscr-page="' + pageNumber + '"]');
      currentPage.classList.remove('active');
      targetPage.classList.add('active');
      this.$set(this, 'activeFormPage', pageNumber);
    },

    /** 
     * This function will allow us to navigate back a page.
     *
     */
    goBack: function() {
      var targetPageId = this.activeFormPage - 1;
      var currentPage = this.$el.querySelector('[data-fscr-page="' + this.activeFormPage + '"]');
      var targetPage  = this.$el.querySelector('[data-fscr-page="' + targetPageId + '"]');
      currentPage.classList.remove('active');
      targetPage.classList.add('active');
      this.$set(this, 'activeFormPage', targetPageId);
      //console.log(currentPage);
    },

    /** 
     * Get a Student
     *
     */
    getStudent: function(id) {
      if(typeof this.students.students[id] != 'undefined') {
        return this.students.students[id];
      }
      return null;
    },


    /**
     | ---------------------------------------------------------------
     | First Page
     | ---------------------------------------------------------------
     |
     | These functions handle submissions on the first page of this form.
     |
     */

     /** 
     * Handle the submission of the first page
     *
     */
    handleFirstPageSubmission: async function() {

      // validate necessary form fields
      var validate = Promise.all([
        this.$validator.validate('first_name'),
        this.$validator.validate('last_name'),
        this.$validator.validate('email'),
        this.$validator.validate('phone'),
        this.$validator.validate('zip_code'),
        this.$validator.validate('pool_access'),
      ]);

      // await results of promise and ensure they are all true
      var validated = await validate.then(result => validated = result.every(isValid => isValid));

      if(validated) {

        // return false if already submitted
        if(this.pages[1].submitted) {
          this.goToPage(2);
          return false;
        }

        // mark this page as submitted
        this.$set(this.pages[1], 'submitted', true);

        // create the FormEntry
        this.createFormEntry();

        // create the Guest
        this.$on('create:form.entry', function() {
          if(this.formEntry.created.id !== null) {
            this.createGuest();
          }
        });

        // go to the next page
        this.$on('create:guest', function() {
          if(this.guest.created.id !== null) {
            this.goToPage(2);
          }
        });

        // finally we'll assume that this guest
        // is the default parent/guardian
        // emulate event to make this easier
        var click = {};
        click.target = {};
        click.target.value = true;
        this.updateGuestParentGuardianForAllStatus(click);

      }

      return false;

    },

    /**
     * This function creates a form entry object in the database
     * to begin our exchange.
     *
     */
    createFormEntry: function() {
      var request = {};
      axios.post(this.api + '/forms/create', request)
        .then(response => {
          // update formEntry id so we know we have one
          this.$set(this.formEntry, 'created', response.data.form);
          // emit event so now we can create guest
          this.$emit('create:form.entry');
        })
        .catch(error => {
          console.log(error.response);
        });
    },

    /**
     * This function creates a guest object in the database.
     *
     */
    createGuest: function() {
      var request = {};
      request.first_name = this.guest.firstName;
      request.last_name = this.guest.lastName;
      request.email_address = this.guest.email;
      request.phone_number = this.guest.phone;
      request.zip_code = this.guest.zipCode;
      request.pool_access = this.guest.poolAccess;
      request.form_entry_id = this.formEntry.created.id;
      axios.post(this.api + '/guests/create', request)
        .then(response => {
          this.$set(this.guest, 'created', response.data.guest);
          this.$emit('create:guest');
        })
        .catch(error => {
          if(typeof error.response.data.errors != 'undefined') {
            var errorResponse = error.response.data.errors;
            for (var e in errorResponse) {
              if(this.guest.errors.hasOwnProperty(e)) {
                this.guest.errors[e] = errorResponse[e][0];
              }
              else {
                this.guest.errors[e] = errorResponse[e][0];
              }
            }
          }
        });
    },


    /**
     | ---------------------------------------------------------------
     | Second Page
     | ---------------------------------------------------------------
     |
     | These functions handle interactions on the second page.
     |
     */

    /**
     * This function calls populateStudents in order to update
     * the live student object.
     *
     */
    changeAmountOfStudents: function(e) {
      this.populateStudents(this.students.count);
    },

    /**
     * This function calls populateParents in order to update
     * the live parent object.
     *
     */
    changeAmountOfParents: function(e) {
      this.populateParents(this.parents.count);
    },

    /**
     * This function updates the live students object
     * when the number of students is changed.
     *
     */
    populateStudents: function(count) {

      count = Number(count);
      var liveStudents = this.students.students,
          newStudents  = [];

      // create the needed amount of students
      for (var i=0; i < count; i++) {
        var student = {
          id: null,
          name: null,
          dob: null,
          dobOriginal: null,
          parentId: null,
          save: null
        };
        if(typeof liveStudents[i] == 'undefined') {
          student.id = i;
          student.save = true;
          newStudents.push(student);
        }
        else {
          student = liveStudents[i];
          student.save = true;
          newStudents.push(student);
        }
      }

      // do not send students to API if they have been removed
      // from front-end
      for(var i=0; i < this.students.students.length; i++) {
        if(i >= count) {
          student = liveStudents[i];
          student.save = false;
          newStudents.push(student);
        }
      }

      // update students object
      this.$set(this.students, 'students', newStudents);

    },

    /**
     * This function updates the live parents object
     * when the number of parents is changed.
     *
     */
    populateParents: function(count) {

      count = Number(count);
      var liveParents = this.parents.parents,
          newParents  = [];

      // create the needed amount of parents
      for (var i=0; i < count; i++) {
        var parent = {
          id: null,
          name: null,
          email: null,
          phone: null,
          students: [],
          errors: {
            students: null
          },
          save: null,
          created: null
        };
        if(typeof liveParents[i] == 'undefined') {
          parent.id = i;
          parent.save = true;
          newParents.push(parent);
        }
        else {
          parent = liveParents[i];
          parent.save = true;
          newParents.push(parent);
        }
      }

      // do not send parents to API if they have been removed
      // from front-end
      for(var i=0; i < this.parents.parents.length; i++) {
        if(i >= count) {
          parent = liveParents[i];
          parent.save = false;
          newParents.push(parent);
        }
      }

      // update parents object
      this.$set(this.parents, 'parents', newParents);

    },

    /**
     * This function updates the which students belong to which 
     * parents/guardians.
     *
     */
    handleParentStudentRelationship: function(parentId, studentId, $e) {

      // check to see if the validation for this field passes here.
      var parentGroup = $e.target.getAttribute('data-parent-group');
      var parentStudentRelationshipFields = this.$refs.parentStudentRelationshipField;
      parentStudentRelationshipFields.forEach(field => {
        if (field.getAttribute('data-parent-group') == parentGroup) {
          this.$validator.errors.remove(field.name);
        }
      });
      
      // get current students object for parent
      var currentStudents = this.parents.parents[parentId].students || [];
      var newStudents = [];

      // build new students object
      currentStudents.forEach(student => newStudents.push(student));

      // add this new student if checkbox is checked
      if($e.target.checked) {
        newStudents.push(studentId);
      }

      // remove if unchecked
      else {
        var indexToRemove = newStudents.indexOf(studentId);
        if(indexToRemove !== -1) newStudents.splice(indexToRemove, 1);
      }

      // push new students object
      this.$set(this.parents.parents[parentId], 'students', newStudents);

      // throw error if no students selected
      if(this.parents.parents[parentId].students.length == 0) {
        this.$set(this.parents.parents[parentId].errors, 'students', 'Please select at least one student.');
      }
      else {
        this.$set(this.parents.parents[parentId].errors, 'students', '');
      }

    },

    /**
     * Adjust a boolean letting us know whether or not
     * we can use the same parent/guardian for each registered student.
     *
     */
    updateGuestParentGuardianForAllStatus: function(e) {

      // if parent is not guardian for all students
      if(e.target.value == 'false') {
        this.$set(this, 'guestIsParentGuardianForAll', false);
      }

      // if parent/guardian for all students
      else {
        this.$set(this, 'guestIsParentGuardianForAll', true);
        this.makeGuestParentGuardianForAll();
      }
        
    },

    makeGuestParentGuardianForAll: function() {

      // collect students
      var students = this.students.students,
          studentIds = [];

      students.forEach(student => {
        studentIds.push(student.id);
      });

      // alter first parent object if one already exists
      if (this.parents.parents.length > 0) {

        for(var i=0; i < this.parents.parents.length; i++) {
          if (i == 0) {
            this.$set(this.parents.parents, i, {
              id: i,
              name: this.guest.firstName + " " + this.guest.lastName,
              email: this.guest.email,
              phone: this.guest.phone,
              students: studentIds,
              errors: {
                students: null
              },
              save: true,
              created: null
            });
          }
          else {
            this.$set(this.parents.parents[i], 'save', false);
          }
        }
      }

      // otherwise create new parent object
      else {
        this.$set(this.parents.parents, 0, {
          id: 0,
          name: this.guest.firstName + " " + this.guest.lastName,
          email: this.guest.email,
          phone: this.guest.phone,
          students: studentIds,
          errors: {
            students: null
          },
          save: true,
          created: null
        });
      }

    },

    /**
     * Add/Update a student's date of birth.
     *
     */
    updateStudentDob: function(e, n) {
      // set date locale options and get date into array
      var date = e;
      this.$set(this.students.students[n], 'dobOriginal', date);
      var dateLocaleOptions = { year: 'numeric', month: 'numeric', day: 'numeric' };
      date = date.toLocaleDateString('en-US', dateLocaleOptions);
      date = date.split('/');

      // create an obj with array
      var dateObj = {};
      dateObj.year = date[2];
      dateObj.month = date[0];
      dateObj.day = date[1];
      for (var datePart in dateObj) {
        if(dateObj[datePart].length < 2) {
          dateObj[datePart] = "0" + dateObj[datePart];
        }
      }
      this.$set(this.students.students[n], 'dob', dateObj);
    },

    /**
     * Handle a submission of the second page.
     *
     */
    handleSecondPageSubmission: async function() {

      // validate necessary form fields
      /*var validate = Promise.all([
        this.$validator.validate('first_name'),
        this.$validator.validate('last_name'),
        this.$validator.validate('email'),
        this.$validator.validate('phone'),
        this.$validator.validate('zip_code'),
        this.$validator.validate('pool_access'),
      ]);*/

      var validateItems = [];

      // validate number students enrolling dropdown
      validateItems.push(this.$validator.validate('number_students_enrolling'));

      // validate dynamic student inputs
      var studentInputs = [];
      studentInputs.push(this.$refs.studentNameInput);
      studentInputs.push(this.$refs.studentDobInput);
      studentInputs.forEach(input => {
        validateItems.push(this.$validator.validate(input.name));
      });

      // validate number parents adding dropdown
      validateItems.push(this.$validator.validate('number_parents_adding'));

      var parentInputs = [];
      parentInputs.push(this.$refs.parentInput);
      parentInputs.forEach(input => {
        validateItems.push(this.$validator.validate(input.name));
      });

      var validate = Promise.all(validateItems);

      // await results of promise and ensure they are all true
      var validated = await validate.then(result => validated = result.every(isValid => isValid));

      console.log('valdiated: ', validated);
      return false;

      if (validated) {

        // return false if already submitted
        if(this.pages[2].submitted) {
          this.goToPage(3);
          return false;
        }

        // mark this page as submitted
        this.$set(this.pages[2], 'submitted', true);

        // in case the user never touched the "Are you the p/g of all" button
        if(this.guestIsParentGuardianForAll) {
          this.makeGuestParentGuardianForAll();
        }

        this.createParents();

        this.$on('create:allGuardians', function() {
          this.createStudents();
        });

        this.goToPage(3);

      }

    },

    createParents: function() {
      
      // create parents
      
      var promises = [];
      
      for(var i=0; i < this.parents.parents.length; i++) {
        
        // created request for each parent
        var request = {};
        request.name = this.parents.parents[i].name;
        request.email = this.parents.parents[i].email;
        request.phone_number = this.parents.parents[i].phone;
        request.form_entry_id = this.formEntry.created.id;

        // push to array of promises
        promises.push(axios.post(this.api + '/guardians/create', request));

      }

      axios.all(promises).then(results => {
        var index = 0;
        results.forEach(response => {
          this.parents.parents[index].created = response.data.guardian;
          index++;
        });
        this.$emit('create:allGuardians');
      }).catch(error => {
        console.log('error: ', error);
      });

    },

    createStudents: function() {
      
      // create students

      var promises = [];

      for(var i=0; i < this.parents.parents.length; i++) {

        var guardianId = this.parents.parents[i].created.id;
        var students = this.parents.parents[i].students;

        students.forEach(studentId => {

          var student = this.getStudent(studentId),
              request = {};

          request.student_name = student.name;
          request.student_date_of_birth = student.dobOriginal;
          request.guardian_id = guardianId;
          request.form_entry_id = this.formEntry.created.id;

          console.log('create student ' + studentId + ' : ', request);
          promises.push(axios.post(this.api + '/students/create', request));

        });

      }

      axios.all(promises).then(results => {
        var index = 0;
        results.forEach(response => {
          this.students.students[index].created = response.data.student;
          index++;
        });
        this.$emit('create:allStudents');
      }).catch(error => {
        console.log('error: ', error);
      });

    },

  }
});

