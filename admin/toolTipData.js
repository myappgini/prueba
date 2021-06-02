var FiltersEnabled = 0; // if your not going to use transitions or filters in any of the tips set this to 0
var spacer="&nbsp; &nbsp; &nbsp; ";

// email notifications to admin
notifyAdminNewMembers0Tip=["", spacer+"No email notifications to admin."];
notifyAdminNewMembers1Tip=["", spacer+"Notify admin only when a new member is waiting for approval."];
notifyAdminNewMembers2Tip=["", spacer+"Notify admin for all new sign-ups."];

// visitorSignup
visitorSignup0Tip=["", spacer+"If this option is selected, visitors will not be able to join this group unless the admin manually moves them to this group from the admin area."];
visitorSignup1Tip=["", spacer+"If this option is selected, visitors can join this group but will not be able to sign in unless the admin approves them from the admin area."];
visitorSignup2Tip=["", spacer+"If this option is selected, visitors can join this group and will be able to sign in instantly with no need for admin approval."];

// contacto table
contacto_addTip=["",spacer+"This option allows all members of the group to add records to the 'Contacto' table. A member who adds a record to the table becomes the 'owner' of that record."];

contacto_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Contacto' table."];
contacto_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Contacto' table."];
contacto_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Contacto' table."];
contacto_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Contacto' table."];

contacto_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Contacto' table."];
contacto_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Contacto' table."];
contacto_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Contacto' table."];
contacto_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Contacto' table, regardless of their owner."];

contacto_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Contacto' table."];
contacto_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Contacto' table."];
contacto_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Contacto' table."];
contacto_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Contacto' table."];

// salary table
salary_addTip=["",spacer+"This option allows all members of the group to add records to the 'Salary' table. A member who adds a record to the table becomes the 'owner' of that record."];

salary_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Salary' table."];
salary_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Salary' table."];
salary_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Salary' table."];
salary_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Salary' table."];

salary_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Salary' table."];
salary_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Salary' table."];
salary_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Salary' table."];
salary_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Salary' table, regardless of their owner."];

salary_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Salary' table."];
salary_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Salary' table."];
salary_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Salary' table."];
salary_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Salary' table."];

// products table
products_addTip=["",spacer+"This option allows all members of the group to add records to the 'Products' table. A member who adds a record to the table becomes the 'owner' of that record."];

products_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Products' table."];
products_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Products' table."];
products_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Products' table."];
products_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Products' table."];

products_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Products' table."];
products_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Products' table."];
products_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Products' table."];
products_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Products' table, regardless of their owner."];

products_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Products' table."];
products_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Products' table."];
products_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Products' table."];
products_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Products' table."];

// db_field_permission table
db_field_permission_addTip=["",spacer+"This option allows all members of the group to add records to the 'Db field permissions' table. A member who adds a record to the table becomes the 'owner' of that record."];

db_field_permission_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Db field permissions' table."];
db_field_permission_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Db field permissions' table."];
db_field_permission_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Db field permissions' table."];
db_field_permission_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Db field permissions' table."];

db_field_permission_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Db field permissions' table."];
db_field_permission_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Db field permissions' table."];
db_field_permission_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Db field permissions' table."];
db_field_permission_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Db field permissions' table, regardless of their owner."];

db_field_permission_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Db field permissions' table."];
db_field_permission_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Db field permissions' table."];
db_field_permission_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Db field permissions' table."];
db_field_permission_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Db field permissions' table."];

// tmp_tables_fields table
tmp_tables_fields_addTip=["",spacer+"This option allows all members of the group to add records to the 'Tmp' table. A member who adds a record to the table becomes the 'owner' of that record."];

tmp_tables_fields_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Tmp' table."];
tmp_tables_fields_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Tmp' table."];
tmp_tables_fields_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Tmp' table."];
tmp_tables_fields_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Tmp' table."];

tmp_tables_fields_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Tmp' table."];
tmp_tables_fields_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Tmp' table."];
tmp_tables_fields_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Tmp' table."];
tmp_tables_fields_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Tmp' table, regardless of their owner."];

tmp_tables_fields_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Tmp' table."];
tmp_tables_fields_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Tmp' table."];
tmp_tables_fields_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Tmp' table."];
tmp_tables_fields_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Tmp' table."];

// view_membership_groups table
view_membership_groups_addTip=["",spacer+"This option allows all members of the group to add records to the 'View mebership group' table. A member who adds a record to the table becomes the 'owner' of that record."];

view_membership_groups_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'View mebership group' table."];
view_membership_groups_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'View mebership group' table."];
view_membership_groups_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'View mebership group' table."];
view_membership_groups_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'View mebership group' table."];

view_membership_groups_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'View mebership group' table."];
view_membership_groups_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'View mebership group' table."];
view_membership_groups_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'View mebership group' table."];
view_membership_groups_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'View mebership group' table, regardless of their owner."];

view_membership_groups_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'View mebership group' table."];
view_membership_groups_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'View mebership group' table."];
view_membership_groups_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'View mebership group' table."];
view_membership_groups_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'View mebership group' table."];

// todos table
todos_addTip=["",spacer+"This option allows all members of the group to add records to the 'Todos' table. A member who adds a record to the table becomes the 'owner' of that record."];

todos_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Todos' table."];
todos_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Todos' table."];
todos_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Todos' table."];
todos_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Todos' table."];

todos_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Todos' table."];
todos_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Todos' table."];
todos_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Todos' table."];
todos_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Todos' table, regardless of their owner."];

todos_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Todos' table."];
todos_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Todos' table."];
todos_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Todos' table."];
todos_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Todos' table."];

/*
	Style syntax:
	-------------
	[TitleColor,TextColor,TitleBgColor,TextBgColor,TitleBgImag,TextBgImag,TitleTextAlign,
	TextTextAlign,TitleFontFace,TextFontFace, TipPosition, StickyStyle, TitleFontSize,
	TextFontSize, Width, Height, BorderSize, PadTextArea, CoordinateX , CoordinateY,
	TransitionNumber, TransitionDuration, TransparencyLevel ,ShadowType, ShadowColor]

*/

toolTipStyle=["white","#00008B","#000099","#E6E6FA","","images/helpBg.gif","","","","\"Trebuchet MS\", sans-serif","","","","3",400,"",1,2,10,10,51,1,0,"",""];

applyCssFilter();
