export default class Student {

  get editable() {
    return [
      'name',
      'dob',
      'parentId'
    ];
  }

  constructor() {
    this.id = null;
    this.name = null;
    this.dob = null;
    this.parentId = null;
  }

  setId(id) {
    this.id = id;
  }

  isEditable(prop) {
    var editable = this.editable;
    return editable.indexOf(prop) !== -1;
  }

}