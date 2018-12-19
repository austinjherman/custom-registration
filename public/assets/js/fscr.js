
Vue.use(VeeValidate, {
  errorBagName: 'validator'
});

var fscrForm = new Vue({
  
  el: '#fscrForm',

  components: {
    'datepicker': vuejsDatepicker
  },

  data: {

    // api base URL
    api: "http://localhost/floridaswim/wp-json/fscr/v1", 

    // display the confirm parent/guardian section?
    confirmParentGuardians: false,

    // guest object
    guest: {
      firstName: "",
      lastName: "",
      email: "",
      phone: "",
      zipCode: "",
      poolAccess: "",
      errors: {
        first_name: "",
        last_name: "",
        email_address: "",
        phone_number: "",
        zip_code: "",
        pool_access: ""
      }
    },

    // students object
    students: {
      count: 0,
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

  methods: {

    updatePoolAccess: function(boolean) {
      if (boolean === true) {
        this.$set(this.guest, 'poolAccess', 'true');
      }
      else {
        this.$set(this.guest, 'poolAccess', 'false');
      }
    },

    /**
     | ---------------------------------------------------------------
     | First Page
     | ---------------------------------------------------------------
     |
     */

    handleFirstPageSubmission: function(event) {
      
      // emit this event so vee validator can catch it
      this.$emit('pageonesubmit');
      var inputs = this.$el.querySelectorAll('.pageOneInput');
      inputs.forEach(function(e) {
        e.dispatchEvent(
          new Event('pageonesubmit')
        );
      });

      // ensure pool access radio has been picked
      if (this.guest.poolAccess === "") {
        this.$set(this.guest.errors, 'pool_access', 'Please select an option.');
        return false;
      }
      else if(this.guest.poolAccess == 'true' || this.guest.poolAccess == 'false') {
        this.$set(this.guest.errors, 'pool_access', '');
      }

      if(this.validator.any()) {
        console.log('there are errors');
        return false;
      }

      // create the form entry
      this.createFormEntry();

    },

    createFormEntry: function() {
      var request = {};
      axios.post(this.api + '/forms/create', request)
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error.response);
        });
    },




    /**
     * This function creates a guest object in javascript 
     * then sends this object as a post request to the guests
     * endpoint. If all goes well, we should expect a 201 created
     * response. 
     *
     * If errors are returned, they are displayed on the frontend.
     *
     */
    createGuest: function() {
      //console.log(this.guest.poolAccess);
      /*
      var request = {};
      request.first_name = this.guest.firstName;
      request.last_name = this.guest.lastName;
      request.email_address = this.guest.email;
      request.phone_number = this.guest.phone;
      request.zip_code = this.guest.zipCode;
      request.pool_access = this.guest.poolAccess;
      axios.post(this.api + '/guests/create', request)
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error.response);
          var errorResponse = error.response.data.errors;
          if(typeof errorResponse != 'undefined') {
            for (var e in this.errors) {
              if(errorResponse.hasOwnProperty(e)) {
                this.errors[e] = errorResponse[e][0];
              }
              else {
                this.errors[e] = "";
              }
            }
          }
        });

        */
    },

    /**
     | ---------------------------------------------------------------
     | Students
     | ---------------------------------------------------------------
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
     * This function updates the live students object
     * when the number of students is changed.
     *
     */
    populateStudents: function(count) {

      count = Number(count);
      var liveStudents = this.students.students,
          newStudents  = [];

      for (var i=0; i < count; i++) {
        var student = {
          id: null,
          name: null,
          dob: null,
          dobOriginal: null,
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
      for(var i=0; i < this.students.students.length; i++) {
        if(i >= count) {
          student = liveStudents[i];
          student.save = false;
          newStudents.push(student);
        }
      }
      this.$set(this.students, 'students', newStudents);
    },

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

     /**
      * Adjust a boolean letting us know whether or not
      * we can use the same parent/guardian for each registered student.
      *
      */
    confirmParentGuardianForAll: function(e) {

      // if parent is not guardian for all students
      if(e.target.value == 'false') {
        this.confirmParentGuardians = true;
      }

      // if parent/guardian for all students
      else {
        
        this.confirmParentGuardians = false;

        // collect student Ids
        var studentIds = [];
        for (var studentId in this.students.students) {
          studentIds.push(studentId);
        }

        // create parent object
        if (this.parents.parents.length > 0) {

          for(var i=0; i < this.parents.parents.length; i++) {
            if (i == 0) {
              this.$set(this.parents.parents, i, {
                id: i,
                first_name: this.guest.firstName,
                last_name: this.guest.lastName,
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

        else {
          this.$set(this.parents.parents, 0, {
            id: 0,
            first_name: this.guest.firstName,
            last_name: this.guest.lastName,
            email: this.guest.email,
            phone: this.guest.phone,
            students: studentIds,
            save: true
          });
        }

      }
        
    },

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

