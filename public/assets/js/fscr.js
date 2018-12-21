
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
          save: false
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
          save: false
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

      console.log('parent count: ', count);
      return;

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
          save: null
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
          newParents.push(parents);
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

      if(this.guestIsParentGuardianForAll) {

        // collect students
        var studentIds = [];
        for (var student in this.students.students) {
          studentIds.push(student.id);
        }

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
                save: true
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
            save: true
          });
        }

      }

      return false;
    },

    /**
     * Handle a submission of the second page.
     *
     */
    handleSecondPageSubmission: async function() {

      /*
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
      */

      if(this.guestIsParentGuardianForAll) {
        this.makeGuestParentGuardianForAll();
      }     

      console.log('students', this.students);
      console.log('parents', this.parents);

    },


    /**
     | ---------------------------------------------------------------
     | Students
     | ---------------------------------------------------------------
     |
     */

    /**
     * Add/Update a student's name.
     *
     */
    updateStudentName: function(e, n) {
      this.$set(this.students.students[n], 'name', e.target.value);
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
     | ---------------------------------------------------------------
     | Parents/Guardians
     | ---------------------------------------------------------------
     |
     */

    addParentGuardianField: function() {
      if (this.parents.count < this.students.count) {
        this.$set(this.parents, 'count', this.parents.count + 1);
        for(var i=0; i < this.parents.count; i++) {
          if(typeof this.parents.parents[i] == 'undefined') {
            this.$set(this.parents.parents, i, {
              first_name: "",
              last_name: "",
              email: "",
              phone: "",
              students: [],
              save: true
            });
          }
          else {
            this.$set(this.parents.parents[i], 'save', true);
          }
        }
      }
    },

    removeParentGuardianField: function() {
      if(this.parents.count > 0) {
        // get last element of array
        var recentParentIndex = this.parents.count - 1;
        this.$set(this.parents.parents[recentParentIndex], 'save', false);
        this.$set(this.parents, 'count', this.parents.count - 1);
      }
    },

    createStudentsAndGuardians: function(e) {
      console.log(this.parents.parents);
    },

  }
});

