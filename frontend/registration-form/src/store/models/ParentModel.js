export default class Parent {

  get editable() {
    return [
      'name',
      'email',
      'phone'
    ];
  }

  constructor() {
    this.id = null;
    this.name = null;
    this.email = null;
    this.phone = null;
    this.students = [];
  }

  setId(id) {
    this.id = id;
  }

  isEditable(prop) {
    var editable = this.editable;
    return editable.indexOf(prop) !== -1;
  }

}