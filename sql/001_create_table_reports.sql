CREATE TABLE reports (
    reportsid int IDENTITY(1,1) PRIMARY KEY,
    name ntext NOT NULL,
    description ntext,
    config_file ntext,
	created datetime default getdate()
);