from django.db import models
from django.utils import timezone
from django.contrib.auth.models import User

class School(models.Model):
	title = models.CharField(max_length=60)

	def __str__(self):
		return self.title

class UserProfile(models.Model):
	user = models.OneToOneField(User,on_delete=models.CASCADE)
	is_administrator = models.BooleanField(default=False)
	is_operator = models.BooleanField(default=False)
	is_student = models.BooleanField(default=False)
	school = models.ManyToManyField(School)

	def __str__(self):
		return self.user.last_name + ' ' + self.user.first_name[:1]

class StudentProfile(models.Model):
	user = models.OneToOneField(User,on_delete=models.CASCADE)
	k_f = models.CharField(max_length=8)

	def __str__(self):
		return self.user.last_name + ' ' + self.user.first_name[:1] + '. (' + self.k_f + ')' 


class StudentFileID(models.Model):
	user = models.OneToOneField(User,on_delete=models.CASCADE)
	fileID = models.FileField(upload_to='students/file1/',null=True, blank=True)
	fileID_date_uploaded = models.DateTimeField(default=timezone.now, null=True, blank=True)

	def __str__(self):
		return self.user.last_name + ' ' + self.user.first_name[:1] + '. (' + self.fileID.url + ')' 

	def delete(self, *args, **kwargs):
		self.fileID.delete()
		self.fileID_date_uploaded = None
		super().delete(*args, **kwargs)

class StudentFileEFKA(models.Model):
	user = models.OneToOneField(User,on_delete=models.CASCADE)
	fileEFKA = models.FileField(upload_to='students/file1/',null=True, blank=True)
	fileEFKA_date_uploaded = models.DateTimeField(default=timezone.now, null=True, blank=True)

	def __str__(self):
		return self.user.last_name + ' ' + self.user.first_name[:1] + '. (' + self.fileEFKA.url + ')' 

	def delete(self, *args, **kwargs):
		self.fileEFKA.delete()
		self.fileEFKA_date_uploaded = None
		super().delete(*args, **kwargs)

class StudentFileIBAN(models.Model):
	user = models.OneToOneField(User,on_delete=models.CASCADE)
	fileIBAN = models.FileField(upload_to='students/file1/',null=True, blank=True)
	fileIBAN_date_uploaded = models.DateTimeField(default=timezone.now, null=True, blank=True)

	def __str__(self):
		return self.user.last_name + ' ' + self.user.first_name[:1] + '. (' + self.fileIBAN.url + ')' 

	def delete(self, *args, **kwargs):
		self.fileIBAN.delete()
		self.fileIBAN_date_uploaded = None
		super().delete(*args, **kwargs)

class StudentFileDOY(models.Model):
	user = models.OneToOneField(User,on_delete=models.CASCADE)
	fileDOY = models.FileField(upload_to='students/file1/',null=True, blank=True)
	fileDOY_date_uploaded = models.DateTimeField(default=timezone.now, null=True, blank=True)

	def __str__(self):
		return self.user.last_name + ' ' + self.user.first_name[:1] + '. (' + self.fileDOY.url + ')' 

	def delete(self, *args, **kwargs):
		self.fileDOY.delete()
		self.fileDOY_date_uploaded = None
		super().delete(*args, **kwargs)