USE [DM]
GO
/****** Object:  Table [dbo].[dm2_link_reestr_department]    Script Date: 01/15/2018 12:51:07 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[dm2_link_reestr_department](
	[id_reestr] [int] NOT NULL,
	[id_department] [int] NOT NULL,
	[date_create] [smalldatetime] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[dm2_ifns]    Script Date: 01/15/2018 12:51:07 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[dm2_ifns](
	[code_no] [varchar](5) NOT NULL,
	[name_no] [varchar](250) NOT NULL,
	[sort] [int] NULL,
	[disable_no] [bit] NULL,
	[date_create] [smalldatetime] NULL,
	[date_edit] [smalldatetime] NULL,
	[log_change] [text] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[code_no] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[dm2_group]    Script Date: 01/15/2018 12:51:07 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[dm2_group](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[group_number] [int] NOT NULL,
	[group_name] [varchar](500) NOT NULL,
	[date_create] [smalldatetime] NULL,
	[date_edit] [smalldatetime] NULL,
	[date_delete] [smalldatetime] NULL,
	[log_change] [text] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[dm2_department]    Script Date: 01/15/2018 12:51:07 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[dm2_department](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[AD_distinguishedName] [varchar](500) NOT NULL,
	[AD_groupName] [varchar](250) NOT NULL,
	[date_create] [smalldatetime] NULL,
	[date_edit] [smalldatetime] NULL,
	[log_change] [text] NOT NULL,
	[name] [varchar](200) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[dm2_user]    Script Date: 01/15/2018 12:51:07 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[dm2_user](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[username] [varchar](250) NOT NULL,
	[fio] [varchar](500) NULL,
	[org_code] [varchar](5) NOT NULL,
	[date_create] [smalldatetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY],
UNIQUE NONCLUSTERED 
(
	[username] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[dm2_role]    Script Date: 01/15/2018 12:51:07 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[dm2_role](
	[rolename] [varchar](50) NOT NULL,
	[description] [varchar](500) NULL,
	[date_create] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[rolename] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[dm2_reestr]    Script Date: 01/15/2018 12:51:07 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[dm2_reestr](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[id_group] [int] NOT NULL,
	[number_model] [varchar](50) NULL,
	[number_typical] [varchar](50) NULL,
	[name_violation] [varchar](5000) NULL,
	[regulations] [varchar](max) NULL,
	[period] [varchar](5) NOT NULL,
	[period_dop] [varchar](10) NULL,
	[description] [varchar](max) NULL,
	[date_create] [smalldatetime] NULL,
	[date_edit] [smalldatetime] NULL,
	[date_delete] [smalldatetime] NULL,
	[log_change] [text] NOT NULL,
	[id_department] [int] NULL,
	[type_violation] [tinyint] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[dm2_user_role]    Script Date: 01/15/2018 12:51:07 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[dm2_user_role](
	[rolename] [varchar](50) NOT NULL,
	[id_user] [int] NOT NULL,
	[date_create] [datetime] NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  View [dbo].[dm2_view_reestr]    Script Date: 01/15/2018 12:51:09 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE view [dbo].[dm2_view_reestr] as

SELECT 
	 [group].[group_number]
	,[group].[group_name]
	,[reestr].[id]
	,[reestr].[id_group]
	,[link].[id_department]
	,[reestr].[number_model]
	,[reestr].[number_typical]
	,[reestr].[name_violation]
	,[reestr].[regulations]
	,[reestr].[period]
	,[reestr].[type_violation] 
	,[department].[AD_groupName]
	,STUFF((SELECT '/№' + [name] FROM [dm2_department] [temp_department]
		JOIN [dm2_link_reestr_department] [temp_link] ON [temp_link].[id_department]=[temp_department].[id]
		WHERE [temp_link].[id_reestr]=[reestr].[id] FOR XML PATH('')		
	),1,1,'') [departments]	
FROM [dm2_reestr] [reestr] 
	JOIN [dm2_group] [group] ON [group].[id]=[reestr].[id_group] 
	LEFT JOIN [dm2_link_reestr_department] [link] ON [link].[id_reestr]=[reestr].[id] 
	JOIN [dm2_department] [department] ON [department].[id]=[link].[id_department] 
WHERE [reestr].[date_delete] IS NULL 
--ORDER BY [group].[group_number], [reestr].[type_violation], [reestr].[number_model], [reestr].[number_typical]
GO
/****** Object:  Table [dbo].[dm2_data]    Script Date: 01/15/2018 12:51:07 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[dm2_data](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[id_reestr] [int] NOT NULL,
	[code_no] [varchar](5) NOT NULL,
	[period_year] [smallint] NOT NULL,
	[doc_all] [int] NOT NULL,
	[doc_violation] [int] NOT NULL,
	[doc_violation_irr] [int] NULL,
	[summ_violation] [decimal](18, 2) NULL,
	[exceeding_duration] [varchar](50) NULL,
	[node] [varchar](max) NULL,
	[author_id] [int] NOT NULL,
	[date_create] [smalldatetime] NULL,
	[date_edit] [smalldatetime] NULL,
	[log_change] [text] NOT NULL,
	[period] [varchar](5) NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[dm2_data_file]    Script Date: 01/15/2018 12:51:07 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[dm2_data_file](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[id_data] [int] NOT NULL,
	[filename_generate] [varchar](500) NOT NULL,
	[filename_original] [varchar](500) NOT NULL,
	[date_create] [datetime] NULL,
	[author_name] [varchar](500) NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Default [DF__dm2_data__date_c__01142BA1]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_data] ADD  DEFAULT (getdate()) FOR [date_create]
GO
/****** Object:  Default [DF__dm2_data___date___18EBB532]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_data_file] ADD  DEFAULT (getdate()) FOR [date_create]
GO
/****** Object:  Default [DF__dm2_depar__date___693CA210]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_department] ADD  DEFAULT (getdate()) FOR [date_create]
GO
/****** Object:  Default [DF__dm2_group__date___778AC167]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_group] ADD  DEFAULT (getdate()) FOR [date_create]
GO
/****** Object:  Default [DF__dm2_ifns__sort__0C85DE4D]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_ifns] ADD  DEFAULT ((0)) FOR [sort]
GO
/****** Object:  Default [DF__dm2_ifns__disabl__0D7A0286]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_ifns] ADD  DEFAULT ((0)) FOR [disable_no]
GO
/****** Object:  Default [DF__dm2_ifns__date_c__0E6E26BF]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_ifns] ADD  DEFAULT (getdate()) FOR [date_create]
GO
/****** Object:  Default [DF__dm2_reest__date___123EB7A3]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_link_reestr_department] ADD  DEFAULT (getdate()) FOR [date_create]
GO
/****** Object:  Default [DF__dm2_reest__date___05D8E0BE]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_reestr] ADD  DEFAULT (getdate()) FOR [date_create]
GO
/****** Object:  Default [DF__dm2_role__date_c__628FA481]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_role] ADD  DEFAULT (getdate()) FOR [date_create]
GO
/****** Object:  Default [DF__dm2_user__date_c__70DDC3D8]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_user] ADD  DEFAULT (getdate()) FOR [date_create]
GO
/****** Object:  Default [DF__dm2_user___date___6477ECF3]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_user_role] ADD  DEFAULT (getdate()) FOR [date_create]
GO
/****** Object:  ForeignKey [FK_dm2_data_dm2_ifns]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_data]  WITH CHECK ADD  CONSTRAINT [FK_dm2_data_dm2_ifns] FOREIGN KEY([code_no])
REFERENCES [dbo].[dm2_ifns] ([code_no])
GO
ALTER TABLE [dbo].[dm2_data] CHECK CONSTRAINT [FK_dm2_data_dm2_ifns]
GO
/****** Object:  ForeignKey [FK_dm2_data_dm2_reestr]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_data]  WITH CHECK ADD  CONSTRAINT [FK_dm2_data_dm2_reestr] FOREIGN KEY([id_reestr])
REFERENCES [dbo].[dm2_reestr] ([id])
GO
ALTER TABLE [dbo].[dm2_data] CHECK CONSTRAINT [FK_dm2_data_dm2_reestr]
GO
/****** Object:  ForeignKey [FK_dm2_data_dm2_user]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_data]  WITH CHECK ADD  CONSTRAINT [FK_dm2_data_dm2_user] FOREIGN KEY([author_id])
REFERENCES [dbo].[dm2_user] ([id])
GO
ALTER TABLE [dbo].[dm2_data] CHECK CONSTRAINT [FK_dm2_data_dm2_user]
GO
/****** Object:  ForeignKey [FK_dm2_data_file_dm2_data]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_data_file]  WITH CHECK ADD  CONSTRAINT [FK_dm2_data_file_dm2_data] FOREIGN KEY([id_data])
REFERENCES [dbo].[dm2_data] ([id])
GO
ALTER TABLE [dbo].[dm2_data_file] CHECK CONSTRAINT [FK_dm2_data_file_dm2_data]
GO
/****** Object:  ForeignKey [FK_dm2_reestr_dm2_department]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_reestr]  WITH CHECK ADD  CONSTRAINT [FK_dm2_reestr_dm2_department] FOREIGN KEY([id_department])
REFERENCES [dbo].[dm2_department] ([id])
GO
ALTER TABLE [dbo].[dm2_reestr] CHECK CONSTRAINT [FK_dm2_reestr_dm2_department]
GO
/****** Object:  ForeignKey [FK_dm2_reestr_dm2_group]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_reestr]  WITH CHECK ADD  CONSTRAINT [FK_dm2_reestr_dm2_group] FOREIGN KEY([id_group])
REFERENCES [dbo].[dm2_group] ([id])
GO
ALTER TABLE [dbo].[dm2_reestr] CHECK CONSTRAINT [FK_dm2_reestr_dm2_group]
GO
/****** Object:  ForeignKey [FK_dm2_user_role_dm2_role]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_user_role]  WITH CHECK ADD  CONSTRAINT [FK_dm2_user_role_dm2_role] FOREIGN KEY([rolename])
REFERENCES [dbo].[dm2_role] ([rolename])
ON UPDATE CASCADE
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[dm2_user_role] CHECK CONSTRAINT [FK_dm2_user_role_dm2_role]
GO
/****** Object:  ForeignKey [FK_dm2_user_role_dm2_user]    Script Date: 01/15/2018 12:51:07 ******/
ALTER TABLE [dbo].[dm2_user_role]  WITH CHECK ADD  CONSTRAINT [FK_dm2_user_role_dm2_user] FOREIGN KEY([id_user])
REFERENCES [dbo].[dm2_user] ([id])
ON UPDATE CASCADE
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[dm2_user_role] CHECK CONSTRAINT [FK_dm2_user_role_dm2_user]
GO
