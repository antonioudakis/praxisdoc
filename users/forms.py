from django import forms
from django.contrib.auth.models import User
from django.contrib.auth.forms import UserCreationForm
from users.models import UserProfile, StudentProfile, StudentFileID

class UserRegisterForm(UserCreationForm):
	email = forms.EmailField()
	last_name = forms.CharField(label='Επώνυμο')
	first_name = forms.CharField(label='Όνομα')

	class Meta:
		model = User
		fields = ['username','email','last_name','first_name','password1','password2']

class UserUpdateForm(forms.ModelForm):
	email = forms.EmailField()
	last_name = forms.CharField(label='Επώνυμο')
	first_name = forms.CharField(label='Όνομα')

	class Meta:
		model = User
		fields = ['username','email','last_name','first_name']

class UserProfileForm(forms.ModelForm):
	is_administrator = forms.BooleanField(label='Διαχειριστής',required=False)
	is_operator = forms.BooleanField(label='Χειριστής',required=False)
	is_student = forms.BooleanField(label='Φοιτητής',required=False)

	class Meta:
		model = UserProfile
		fields = []

class StudentProfileForm(forms.ModelForm):
	k_f = forms.CharField(label='Α.Μ.')

	class Meta:
		model = StudentProfile
		fields = ['k_f']

class StudentFileIDForm(forms.ModelForm):
	fileID = forms.FileField(label='fileID',required=False)
	fileID_date_uploaded = forms.DateField(label='Ημ/νία',required=False)

	class Meta:
		model = StudentFileID
		fields = ['fileID','fileID_date_uploaded']
