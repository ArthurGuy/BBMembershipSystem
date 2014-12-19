App.User = DS.Model.extend({
    given_name: DS.attr('string'),
    family_name: DS.attr('string'),
    email: DS.attr('string'),

    roles: DS.hasMany('role')
});